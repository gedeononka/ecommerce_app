<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\CartItem;
use Barryvdh\DomPDF\Facade\Pdf;  
use App\Notifications\OrderStatusUpdated;
use App\Notifications\PaymentConfirmed;
use App\Notifications\OrderCreated;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.product')
            ->where('user_id', Auth::id()); 

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('period')) {
            $days = (int) $request->period;
            $query->where('created_at', '>=', Carbon::now()->subDays($days));
        }

        $orders = $query->latest()->paginate(10)->withQueryString(); 

        return view('client.orders.index', compact('orders'));
    }

    public function create()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        return view('client.orders.create', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:Paiement avant livraison,Paiement après livraison',
        ]);

        $user = Auth::user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        if ($request->payment_method === 'Paiement avant livraison') {
            // Stocker les infos en session
            session([
                'order_data' => $request->only(['address', 'phone', 'payment_method']),
                'cart_items' => $cartItems,
                'total_amount' => $total
            ]);

            return redirect()->route('stripe.checkout');
        }

        // Paiement après livraison - créer la commande directement
        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'address' => $request->address,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'total_amount' => $total,
                'status' => 'en attente',
                'payment_status' => 'non payé',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);
            }

            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            $user->notify(new OrderCreated($order));

            return redirect()->route('orders.show', $order->id)
                             ->with('success', 'Commande passée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la commande : ' . $e->getMessage());
        }
    }

    public function stripeCheckout()
    {
        $user = Auth::user();
        $cartItems = session('cart_items');
        $total = session('total_amount');

        if (!$cartItems || !$total) {
            return redirect()->route('cart.index')->with('error', 'Panier vide ou expiré.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];

        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => $item->product->price * 100, // en centimes
                ],
                'quantity' => $item->quantity,
            ];
        }

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
            'customer_email' => $user->email,
        ]);

        return redirect($checkoutSession->url);
    }

   public function checkoutSuccess()
{
    $user = Auth::user();
    $cartItems = session('cart_items');
    $orderData = session('order_data');
    $total = session('total_amount');

    if (!$cartItems || !$orderData) {
        return redirect()->route('cart.index')->with('error', 'Session expirée.');
    }

    DB::beginTransaction();

    try {
        $order = Order::create([
            'user_id' => $user->id,
            'address' => $orderData['address'],
            'phone' => $orderData['phone'],
            'payment_method' => 'Paiement avant livraison',
            'total_amount' => $total,
            'status' => 'en attente',
            'payment_status' => 'payé',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ]);
        }

        CartItem::where('user_id', $user->id)->delete();

        DB::commit();

        // Générer la facture PDF et la stocker
        $order->load('items.product', 'user');
        $pdf = Pdf::loadView('client.orders.invoice', compact('order'));
        $fileName = "facture_commande_{$order->id}.pdf";
        Storage::put("public/invoices/{$fileName}", $pdf->output());

        session()->forget(['cart_items', 'order_data', 'total_amount']);

        $user->notify(new OrderCreated($order));

        return redirect()->route('orders.show', $order)->with('success', 'Paiement effectué. Commande enregistrée.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('cart.index')->with('error', 'Erreur lors de la création de la commande.');
    }
}

    public function checkoutCancel()
    {
        return redirect()->route('cart.index')->with('error', 'Paiement annulé.');
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('client.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'user');
        $pdf = Pdf::loadView('client.orders.invoice', compact('order'));
        return $pdf->download("facture_commande_{$order->id}.pdf");
    }

    public function update(Request $request, Order $order)
    {
        $order->status = $request->status;
        $order->payment_status = $request->payment_status;
        $order->save();

        $order->user->notify(new OrderStatusUpdated($order));

        if ($order->payment_status === 'payé') {
            $order->user->notify(new PaymentConfirmed($order));
        }

        return redirect()->back()->with('success', 'Commande mise à jour');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        if ($order->status !== 'en attente') {
            return redirect()->back()->with('error', 'Seules les commandes en attente peuvent être annulées.');
        }

        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès.');
    }

    public function reorder(Order $order)
    {
        DB::beginTransaction();

        try {
            $newOrder = Order::create([
                'user_id' => auth()->id(),
                'address' => $order->address,
                'phone' => $order->phone,
                'payment_method' => $order->payment_method,
                'total_amount' => $order->total_amount,
                'status' => 'en attente',
                'payment_status' => $order->payment_method === 'Paiement avant livraison' ? 'payé' : 'non payé',
            ]);

            foreach ($order->items as $item) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);
            }

            DB::commit();

            $order->user->notify(new OrderCreated($newOrder));

            return redirect()->route('orders.show', $newOrder)->with('success', 'Commande repassée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du reorder : ' . $e->getMessage());
        }
    }
}
