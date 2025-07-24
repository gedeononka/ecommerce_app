@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Navigation breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
        <a href="{{ route('products.index') }}" class="hover:text-blue-600">Catalogue</a>
        <span>/</span>
        <span class="text-gray-800">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
            <!-- Images du produit -->
            <div>
                <!-- Image principale -->
                <div class="mb-4">
                    @if($product->image)
                        <img id="mainImage" 
                             src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-96 object-cover rounded-lg">
                    @else
                        <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Images supplémentaires (optionnel) -->
                @if($product->images && count($product->images) > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition duration-200"
                                 onclick="changeMainImage(this.src)">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Informations du produit -->
            <div>
                <!-- Nom et prix -->
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                
                <div class="flex items-center justify-between mb-6">
                    <div class="text-4xl font-bold text-blue-600">
                        {{ number_format($product->price, 0, ',', ' ') }} FCFA
                    </div>
                    
                    <!-- Statut de disponibilité -->
                    <div class="text-right">
                        @if($product->stock > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                En stock ({{ $product->stock }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                                </svg>
                                Rupture de stock
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Description courte -->
                @if($product->short_description)
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <p class="text-gray-700">{{ $product->short_description }}</p>
                    </div>
                @endif

                <!-- Formulaire d'ajout au panier -->
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', ['id' => $product->id]) }}" method="POST" class="mb-8">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="flex items-center gap-4 mb-4">
                            <label for="quantity" class="text-sm font-medium text-gray-700">Quantité:</label>
                            <div class="flex items-center">
                                <button type="button" onclick="decreaseQuantity()" 
                                        class="w-10 h-10 border border-gray-300 rounded-l-md bg-gray-50 hover:bg-gray-100 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 10h10"/>
                                    </svg>
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity"
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock }}"
                                       class="w-16 h-10 border-t border-b border-gray-300 text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" onclick="increaseQuantity()" 
                                        class="w-10 h-10 border border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 5v10m-5-5h10"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition duration-200 font-semibold text-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                            </svg>
                            Ajouter au panier
                        </button>
                    </form>
                @else
                    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-8">
                        <p class="text-red-800 font-medium">Ce produit n'est actuellement pas disponible.</p>
                    </div>
                @endif

                <!-- Informations supplémentaires -->
                <div class="space-y-4">
                    @if($product->category)
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="font-medium mr-2">Catégorie:</span>
                            <a href="{{ route('products.index', ['category' => $product->category->id]) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    @endif

                    @if($product->sku)
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="font-medium mr-2">Référence:</span>
                            <span>{{ $product->sku }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Description longue -->
        @if($product->description)
            <div class="border-t border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Description</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        @endif

        <!-- Produits similaires -->
        @if($relatedProducts && count($relatedProducts) > 0)
            <div class="border-t border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Produits similaires</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition duration-200">
                            <div class="h-32 bg-gray-200 rounded-md mb-3">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                                         alt="{{ $relatedProduct->name }}"
                                         class="w-full h-full object-cover rounded-md">
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-2 truncate">{{ $relatedProduct->name }}</h3>
                            <p class="text-blue-600 font-bold mb-2">{{ number_format($relatedProduct->price, 0, ',', ' ') }} FCFA</p>
                            <a href="{{ route('products.show', $relatedProduct) }}" 
                               class="text-blue-600 text-sm hover:text-blue-800">
                                Voir détails →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    const maxStock = {{ $product->stock }};

    function changeMainImage(src) {
        document.getElementById('mainImage').src = src;
    }

    function increaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue < maxStock) {
            input.value = currentValue + 1;
        }
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }

    // Gestion des messages flash
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
</script>
@endsection