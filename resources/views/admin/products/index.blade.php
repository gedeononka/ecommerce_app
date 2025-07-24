@extends('layouts.app')

@section('title', 'Liste des produits')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Liste des produits</h1>
        <a href="{{ route('admin.products.create') }}"
            class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
            ➕ Ajouter un produit
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($products->count())
        <table class="w-full table-auto border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Image</th>
                    <th class="p-2 text-left">Nom</th>
                    <th class="p-2 text-left">Prix</th>
                    <th class="p-2 text-left">Stock</th>
                    <th class="p-2 text-left">Catégorie</th>
                    <th class="p-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="border-t">
                        <td class="p-2">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="image"
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-gray-400 italic">Aucune</span>
                            @endif
                        </td>
                        <td class="p-2">{{ $product->name }}</td>
                        <td class="p-2">{{ number_format($product->price, 2) }} FCFA</td>
                        <td class="p-2">{{ $product->stock }}</td>
                        <td class="p-2">{{ $product->category->name ?? 'Non défini' }}</td>
                        <td class="p-2 text-center">
                            <a href="{{ route('admin.products.show', $product) }}"
                               class="text-blue-500 hover:underline mr-2">👁️ voir</a>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="text-yellow-500 hover:underline mr-2">✏️ modifier</a>
                            <form action="{{ route('admin.products.destroy', $product) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">🗑️supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <p class="text-gray-500">Aucun produit trouvé.</p>
    @endif
</div>
@endsection
