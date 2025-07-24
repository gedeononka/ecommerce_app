@extends('layouts.app')

@section('title', 'Modifier commande')

@section('content')
<div class="p-6 bg-white rounded shadow max-w-xl mx-auto">
    <h1 class="text-xl font-bold mb-4">Modifier la commande #{{ $order->id }}</h1>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut de la commande</label>
            <select name="status" class="w-full border px-3 py-2 rounded">
                @foreach(['en attente', 'expédiée', 'livrée', 'annulée'] as $status)
                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement</label>
            <select name="payment_method" class="w-full border px-3 py-2 rounded">
                <option value="Paiement avant livraison" {{ $order->payment_method === 'Paiement avant livraison' ? 'selected' : '' }}>
                    Paiement avant livraison
                </option>
                <option value="Paiement après livraison" {{ $order->payment_method === 'Paiement après livraison' ? 'selected' : '' }}>
                    Paiement après livraison
                </option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut du paiement</label>
            <select name="payment_status" class="w-full border px-3 py-2 rounded">
                <option value="payé" {{ $order->payment_status === 'payé' ? 'selected' : '' }}>Payé</option>
                <option value="non payé" {{ $order->payment_status === 'non payé' ? 'selected' : '' }}>Non payé</option>
            </select>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 hover:underline">← Retour</a>
            <button type="submit" class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                ✅ Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
