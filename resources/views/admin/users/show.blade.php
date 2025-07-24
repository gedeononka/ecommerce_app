@extends('layouts.app')

@section('title', 'Détail utilisateur')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Détail de l’utilisateur</h1>

    {{-- Informations utilisateur --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Informations personnelles</h2>
        <p><strong>Nom :</strong> {{ $user->name }}</p>
        <p><strong>Email :</strong> {{ $user->email }}</p>
        <p><strong>Rôles :</strong> {{ $user->getRoleNames()->join(', ') ?: '—' }}</p>
    </div>

    {{-- Historique des commandes --}}
    <div>
        <h2 class="text-xl font-semibold mb-2">Historique des commandes</h2>

        @if($user->orders->count())
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">ID Commande</th>
                        <th class="border border-gray-300 px-4 py-2">Date</th>
                        <th class="border border-gray-300 px-4 py-2">Montant total</th>
                        <th class="border border-gray-300 px-4 py-2">Statut</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-4 py-2">{{ $order->id }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                        <td class="border border-gray-300 px-4 py-2">{{ ucfirst($order->status) }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">Voir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Aucune commande trouvée pour cet utilisateur.</p>
        @endif
    </div>
</div>
@endsection
