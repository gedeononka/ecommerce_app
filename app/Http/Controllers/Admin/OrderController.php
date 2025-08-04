<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\PaymentConfirmed;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        // Validation
        $validated = $request->validate([
            'status' => 'required|string|in:en attente,expédiée,livrée,annulée',
            'payment_method' => 'required|string|in:Paiement avant livraison,Paiement après livraison',
            'payment_status' => 'nullable|string|in:payé,non payé',
        ]);

        // Mise à jour
        $order->status = $validated['status'];
        $order->payment_method = $validated['payment_method'];

        if (isset($validated['payment_status'])) {
            $order->payment_status = $validated['payment_status'];
        }

        $order->save();

        // ✅ Notification : statut modifié
        $order->user->notify(new OrderStatusUpdated($order));

        // ✅ Paiement confirmé → notifier + générer facture si besoin
        if ($order->payment_status === 'payé') {
            $order->user->notify(new PaymentConfirmed($order));

            // Générer la facture PDF si elle n'existe pas déjà
            $invoicePath = storage_path("app/factures/facture_commande_{$order->id}.pdf");

            if (!file_exists($invoicePath)) {
                $order->load('items.product', 'user');
                $pdf = Pdf::loadView('client.orders.invoice', compact('order'));
                $pdf->save($invoicePath);
            }
        }

        return redirect()->route('admin.orders.show', $order)
                         ->with('success', 'Commande mise à jour avec succès.');
    }
}
