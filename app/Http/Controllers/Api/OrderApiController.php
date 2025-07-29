<?php
namespace App\Http\Controllers\Api;

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
class OrderApiController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->orders()->with('items.product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('days')) {
            $query->where('created_at', '>=', now()->subDays($request->days));
        }

        return response()->json($query->paginate(10));
    }

    public function create()
    {
        $items = auth()->user()->cartItems()->with('product')->get();
        if ($items->isEmpty()) {
            return response()->json(['message' => 'Panier vide'], 400);
        }
        return response()->json(['cart' => $items]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:Paiement avant livraison,Paiement après livraison',

        ]);
         if ($request->payment_method === 'Paiement avant livraison') {
    $request->validate([
        'account_number' => 'required|string|min:4|max:255'
    ]);
}

        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Panier vide'], 400);
        }

        DB::beginTransaction();
        try {
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'phone' => $request->phone,
            'payment_method' => $request->payment_method,
            'total_amount' => $total,
            'status' => 'en attente',
            'payment_status' => $request->payment_method === 'Paiement avant livraison' ? 'payé' : 'non payé',
        ]);

            foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ]);
        }

            $order->update(['total' => $total]);

            $user->cartItems()->delete();

            Notification::send($user, new OrderCreated($order));

            DB::commit();
            return response()->json(['message' => 'Commande créée', 'order' => $order]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Échec de la commande'], 500);
        }
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product');
        return response()->json($order);
    }

    public function invoice(Order $order)
    {
        $this->authorize('view', $order);

        $pdf = PDF::loadView('client.orders.invoice', ['order' => $order]);
        return $pdf->download("facture-commande-{$order->id}.pdf");
    }

    public function update(Request $request, Order $order)
    {
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        Notification::send($order->user, new OrderStatusUpdated($order));

        if ($order->payment_status === 'payé') {
            Notification::send($order->user, new PaymentConfirmed($order));
        }

        return response()->json(['message' => 'Commande mise à jour']);
    }

    public function cancel(Order $order)
    {
        

        $order->items()->delete();
        $order->delete();

        return response()->json(['message' => 'Commande annulée']);
    }

    public function reorder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        DB::beginTransaction();
        try {
            $newOrder = auth()->user()->orders()->create([
                'address' => $order->address,
                'phone' => $order->phone,
                'payment_method' => $order->payment_method,
                'status' => 'en attente',
                'payment_status' => 'non payé',
            ]);

            foreach ($order->items as $item) {
                $newOrder->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            Notification::send(auth()->user(), new OrderCreated($newOrder));
            DB::commit();
            return response()->json(['message' => 'Commande dupliquée', 'order' => $newOrder]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur duplication'], 500);
        }
    }
}
