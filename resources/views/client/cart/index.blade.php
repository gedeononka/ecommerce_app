@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mon Panier</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems && $cartItems->count() > 0)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="hidden md:grid md:grid-cols-6 gap-4 items-center pb-4 border-b border-gray-200 font-semibold text-gray-700">
                    <div class="col-span-2">Produit</div>
                    <div class="text-center">Prix unitaire</div>
                    <div class="text-center">Quantité</div>
                    <div class="text-center">Total</div>
                    <div class="text-center">Action</div>
                </div>

                @foreach($cartItems as $item)
                    <div class="border-b border-gray-200 py-6 last:border-b-0">
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-center">
                            <!-- Produit -->
                            <div class="col-span-1 md:col-span-2">
                                <div class="flex items-center space-x-4">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $item->product->name ?? 'Produit inconnu' }}</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $item->product->category->name ?? 'Sans catégorie' }}
                                        </p>
                                        @if(($item->product->stock ?? 0) < $item->quantity)
                                            <p class="text-xs text-red-600 mt-1">
                                                Stock insuffisant ({{ $item->product->stock ?? 0 }} disponibles)
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Prix unitaire -->
                            <div class="text-center">
                                <span class="font-semibold text-gray-800">
                                    {{ isset($item->product->price) ? number_format($item->product->price, 0, ',', ' ') . ' FCFA' : 'Prix indisponible' }}
                                </span>
                            </div>

                            <!-- Quantité -->
                            <div class="text-center">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    @method('PUT')
                                    <button type="button" onclick="decrementQuantity({{ $item->id }})"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-600 w-8 h-8 rounded-l-md flex items-center justify-center">-</button>
                                    <input type="number"
                                           name="quantity"
                                           id="quantity-{{ $item->id }}"
                                           value="{{ $item->quantity }}"
                                           min="1"
                                           max="{{ $item->product->stock ?? 99 }}"
                                           class="w-16 h-8 text-center border-t border-b border-gray-200 focus:outline-none"
                                           onchange="updateQuantity({{ $item->id }})">
                                    <button type="button" onclick="incrementQuantity({{ $item->id }})"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-600 w-8 h-8 rounded-r-md flex items-center justify-center">+</button>
                                </form>
                            </div>

                            <!-- Total -->
                            <div class="text-center">
                                <span class="font-bold text-gray-800">
                                    {{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', ' ') }} FCFA
                                </span>
                            </div>

                            <!-- Action -->
                            <div class="text-center">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')"
                                            class="text-red-600 hover:text-red-800 transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 112 0v4a1 1 0 11-2 0V9zm4 0a1 1 0 112 0v4a1 1 0 11-2 0V9z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Résumé -->
            <div class="bg-gray-50 p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">Total articles: {{ $cartItems->sum('quantity') }}</p>
                        <p class="text-sm text-gray-600">Frais de livraison: Gratuit</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-800">
                            Total: 
                            {{ number_format($cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity), 0, ',', ' ') }} FCFA
                        </p>
                        <div class="mt-4 space-x-4">
                            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">Continuer mes achats</a>
                            <a href="{{ route('orders.create') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">Effectuer la commande</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="mt-6 flex justify-between">
            <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')"
                        class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                    Vider le panier
                </button>
            </form>

        </div>
    @else
        <!-- Panier vide -->
        <div class="text-center py-16">
            <div class="mx-auto w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Votre panier est vide</h2>
            <p class="text-gray-600 mb-8">Découvrez nos produits et ajoutez-les à votre panier.</p>
            <a href="{{ route('products.index') }}"
               class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                🛍️ Voir nos produits
            </a>
        </div>
    @endif
</div>

<script>
function updateQuantity(itemId) {
    const form = document.querySelector(`#quantity-${itemId}`).closest('form');
    form.submit();
}

function incrementQuantity(itemId) {
    const input = document.querySelector(`#quantity-${itemId}`);
    const max = parseInt(input.getAttribute('max'));
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
        updateQuantity(itemId);
    }
}

function decrementQuantity(itemId) {
    const input = document.querySelector(`#quantity-${itemId}`);
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        updateQuantity(itemId);
    }
}


</script>
@endsection
