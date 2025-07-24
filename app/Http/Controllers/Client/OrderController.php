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

class OrderController extends Controller
{
    
public function index(Request $request)
{
    $query = Order::with('items.product')
        ->where('user_id', Auth::id()); // <-- filtre les commandes du client connecté

    // Filtrer par statut
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filtrer par période (en jours)
    if ($request->filled('period')) {
        $days = (int) $request->period;
        $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    $orders = $query->latest()->paginate(10)->withQueryString(); // important pour garder les filtres dans la pagination

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
    if ($request->payment_method === 'Paiement avant livraison') {
    $request->validate([
        'account_number' => 'required|string|min:4|max:255'
    ]);
}

    $user = Auth::user();
    $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
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

        // Vider le panier
        CartItem::where('user_id', $user->id)->delete();

        DB::commit();

        // ✅ Envoyer une notification de confirmation de commande
        $user->notify(new OrderCreated($order));

        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Commande passée avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Erreur lors de la commande : ' . $e->getMessage());
    }
}


    public function show(Order $order)
{
    $order->load('items.product');
    return view('client.orders.show', compact('order'));
}
 public function invoice(Order $order)
{
    // Charge les relations nécessaires
    $order->load('items.product', 'user');

    // Génère le PDF à partir de la vue 'client.orders.invoice' avec les données $order
    $pdf = pdf::loadView('client.orders.invoice', compact('order'));

    // Retourne le PDF en téléchargement avec un nom de fichier personnalisé
    return $pdf->download("facture_commande_{$order->id}.pdf");
}
public function update(Request $request, Order $order)
{
    $order->status = $request->status;
    $order->payment_status = $request->payment_status;
    $order->save();

    // Notification pour le statut
    $order->user->notify(new OrderStatusUpdated($order));

    // Notification si paiement confirmé
    if ($order->payment_status === 'payé') {
        $order->user->notify(new PaymentConfirmed($order));
    }

    return redirect()->back()->with('success', 'Commande mise à jour');
}
public function cancel(Order $order)
{
    // Vérifie que l'utilisateur connecté est bien le propriétaire de la commande
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Action non autorisée.');
    }

    // Autorise uniquement l'annulation si la commande est "en attente"
    if ($order->status !== 'en attente') {
        return redirect()->back()->with('error', 'Seules les commandes en attente peuvent être annulées.');
    }

    // Supprime les articles liés à la commande (order_items)
    $order->items()->delete();

    // Supprime la commande elle-même
    $order->delete();

    // Redirige vers la liste des commandes avec un message de succès
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

        // Notification de nouvelle commande
        $order->user->notify(new OrderCreated($newOrder));

        return redirect()->route('orders.show', $newOrder)->with('success', 'Commande repassée avec succès.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Erreur lors du reorder : ' . $e->getMessage());
    }
}


}
