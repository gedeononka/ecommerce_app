@extends('layouts.app')

@section('title', 'Détail commande')

@section('content')
<div class="p-6 bg-white rounded shadow max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Détail de la commande #{{ $order->id }}</h1>

    <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Informations client</h2>
        <p><strong>Nom :</strong> {{ $order->user->name ?? '—' }}</p>
        <p><strong>Email :</strong> {{ $order->user->email ?? '—' }}</p>
        <p><strong>Téléphone :</strong> {{ $order->phone }}</p>
        <p><strong>Adresse de livraison :</strong> {{ $order->address }}</p>
    </section>

    <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Détails de la commande</h2>
        <p><strong>Montant total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</p>
        <p><strong>Mode de paiement :</strong> {{ $order->payment_method }}</p>
        <p><strong>Statut commande :</strong> 
            <span class="font-semibold">
                {{ ucfirst($order->status) }}
            </span>
        </p>
        <p><strong>Statut paiement :</strong> 
            @if($order->payment_status === 'payé')
                <span class="text-green-600 font-semibold">Payé</span>
            @elseif($order->payment_status === 'non payé')
                <span class="text-red-600 font-semibold">Non payé</span>
            @else
                <span>—</span>
            @endif
        </p>
    </section>

    <section>
        <h2 class="text-xl font-semibold mb-2">Produits commandés</h2>
        @if($order->items->count())
            <table class="w-full border border-gray-200 rounded">
                <thead class="bg-gray-100 text-left text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border-b">Produit</th>
                        <th class="px-4 py-2 border-b">Quantité</th>
                        <th class="px-4 py-2 border-b">Prix unitaire</th>
                        <th class="px-4 py-2 border-b">total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $item->product->name ?? 'Produit supprimé' }}</td>
                            <td class="px-4 py-2 border-b">{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2, ',', ' ') }} FCFA</td>                           
                            <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} FCFA</td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucun produit dans cette commande.</p>
        @endif
    </section>

    <div class="mt-6">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">&larr; Retour à la liste des commandes</a>
    </div>
</div>
@endsection
