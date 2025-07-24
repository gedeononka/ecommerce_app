@extends('layouts.app')

@section('title', 'Liste des commandes')

@section('content')
<div class="p-6 bg-white rounded shadow overflow-x-auto">
    <h1 class="text-2xl font-bold mb-6">Liste des commandes clients</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->count())
        <table class="min-w-full border border-gray-200 rounded">
            <thead class="bg-gray-100 text-left text-gray-700">
                <tr>
                    <th class="px-4 py-2 border-b">#ID</th>
                    <th class="px-4 py-2 border-b">Client</th>
                    <th class="px-4 py-2 border-b">Adresse</th>
                    <th class="px-4 py-2 border-b">Téléphone</th>
                    <th class="px-4 py-2 border-b">Montant total</th>
                    <th class="px-4 py-2 border-b">Mode de paiement</th>
                    <th class="px-4 py-2 border-b">Statut commande</th>
                    <th class="px-4 py-2 border-b">Statut paiement</th>
                    <th class="px-4 py-2 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border-b">{{ $order->id }}</td>
                    <td class="px-4 py-2 border-b">{{ $order->user->name ?? '—' }}</td>
                    <td class="px-4 py-2 border-b max-w-xs truncate" title="{{ $order->address }}">{{ $order->address }}</td>
                    <td class="px-4 py-2 border-b">{{ $order->phone }}</td>
                    <td class="px-4 py-2 border-b font-semibold text-blue-600">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                    <td class="px-4 py-2 border-b">{{ $order->payment_method }}</td>
                    <td class="px-4 py-2 border-b">
                        @php
                            $colors = [
                                'en attente' => 'bg-yellow-200 text-yellow-800',
                                'expédiée' => 'bg-blue-200 text-blue-800',
                                'livrée' => 'bg-green-200 text-green-800',
                                'annulée' => 'bg-red-200 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $colors[$order->status] ?? 'bg-gray-200 text-gray-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border-b">
                        @if($order->payment_status === 'payé')
                            <span class="text-green-600 font-semibold">Payé</span>
                        @elseif($order->payment_status === 'non payé')
                            <span class="text-red-600 font-semibold">Non payé</span>
                        @else
                            <span class="text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border-b text-center">
                       <div class="flex gap-3 justify-center">
    <a href="{{ route('admin.orders.show', $order) }}"
       class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-blue-200 transition">
        👁️ Voir
    </a>

    <a href="{{ route('admin.orders.edit', $order) }}"
       class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-yellow-200 transition">
        ✏️ Modifier
    </a>
</div>


                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <p class="text-gray-600">Aucune commande trouvée.</p>
    @endif
</div>
@endsection
