@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<div class="p-6 bg-white rounded shadow space-y-6">
    <h1 class="text-2xl font-bold">📊 Statistiques et tableaux de bord</h1>

    <!-- Chiffres clés -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="bg-blue-100 p-4 rounded shadow">
            <div class="text-xl font-bold text-blue-800">{{ number_format($totalRevenue, 2, ',', ' ') }} FCFA</div>
            <div class="text-sm text-gray-600">Chiffre d'affaires total</div>
        </div>
        <div class="bg-green-100 p-4 rounded shadow">
            <div class="text-xl font-bold text-green-800">{{ $totalOrders }}</div>
            <div class="text-sm text-gray-600">Commandes totales</div>
        </div>
        <div class="bg-yellow-100 p-4 rounded shadow">
            <div class="text-xl font-bold text-yellow-800">{{ $totalProductsSold }}</div>
            <div class="text-sm text-gray-600">Produits vendus</div>
        </div>
        <div class="bg-indigo-100 p-4 rounded shadow">
            <div class="text-xl font-bold text-indigo-800"> {{ number_format($monthlyRevenue, 2, ',', ' ') }} FCFA</div>
            <div class="text-sm text-gray-600">CA (ce mois)</div>
        </div>
        <div class="bg-purple-100 p-4 rounded shadow">
            <div class="text-xl font-bold text-purple-800">{{ $monthlyOrders }}</div>
            <div class="text-sm text-gray-600">Commandes (ce mois)</div>
        </div>
        <div class="bg-pink-100 p-4 rounded shadow">
            <div class="text-xl font-bold text-pink-800">{{ $totalUsers }}</div>
            <div class="text-sm text-gray-600">Utilisateurs enregistrés</div>
        </div>
    </div>

    <!-- Top produits -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-2">🔥 Meilleurs produits</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto bg-white border border-gray-200 rounded shadow-sm">
                <thead>
                    <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                        <th class="px-4 py-2">Produit</th>
                        <th class="px-4 py-2">Quantité vendue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topProducts as $product)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $product->name }}</td>
                            <td class="px-4 py-2">{{ $product->total_sold ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-center text-gray-500">Aucun produit vendu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
