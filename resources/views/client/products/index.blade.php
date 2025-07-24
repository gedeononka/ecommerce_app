@extends('layouts.app')

@section('title', 'Catalogue de produits')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Catalogue de produits</h1>
    
    <!-- Barre de recherche et filtres -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row gap-4">
            <!-- Recherche par mots-clés -->
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Rechercher un produit..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            
            <!-- Filtre par catégorie -->
            <div class="md:w-48">
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Bouton de recherche -->
            <button type="submit"  class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                Rechercher
            </button>
        </form>
    </div>

    <!-- Résultats de recherche -->
    @if(request('search') || request('category'))
        <div class="mb-6">
            <p class="text-gray-600">
                {{ $products->total() }} résultat(s) trouvé(s)
                @if(request('search'))
                    pour "{{ request('search') }}"
                @endif
                @if(request('category'))
                    dans {{ $categories->find(request('category'))->name ?? 'cette catégorie' }}
                @endif
            </p>
        </div>
    @endif

    <!-- Grille de produits -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                <!-- Image du produit -->
                <div class="h-48 bg-gray-200 relative">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badge de disponibilité -->
                    @if($product->stock <= 0)
                        <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-md text-sm">
                            Rupture
                        </div>
                    @elseif($product->stock <= 5)
                        <div class="absolute top-2 right-2 bg-orange-500 text-white px-2 py-1 rounded-md text-sm">
                            Stock faible
                        </div>
                    @endif
                </div>

                <!-- Informations du produit -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate">
                        {{ $product->name }}
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                        {{ Str::limit($product->description, 100) }}
                    </p>
                    
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-2xl font-bold text-blue-600">
                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                        </span>
                        <span class="text-sm text-gray-500">
                            Stock: {{ $product->stock }}
                        </span>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('products.show', $product) }}" 
                           class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-center hover:bg-gray-200 transition duration-200">
                            Voir détails
                        </a>
                        
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', ['id' => $product->id]) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" 
                                       class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                                    Ajouter au panier
                                </button>
                            </form>
                        @else
                            <button disabled 
                                    class="flex-1 bg-gray-300 text-gray-500 px-4 py-2 rounded-md cursor-not-allowed">
                                Indisponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">Aucun produit trouvé</h3>
                <p class="text-gray-500">Essayez de modifier vos critères de recherche.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="flex justify-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    // Gestion des messages flash
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
</script>
@endsection