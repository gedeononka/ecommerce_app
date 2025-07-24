@extends('layouts.app')

@section('title', 'Détails du produit')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">{{ $product->name }}</h1>

    <div class="mb-4">
        <strong>Description :</strong>
        <p>{{ $product->description }}</p>
    </div>

    @if ($product->long_description)
        <div class="mb-4">
            <strong>Description longue :</strong>
            <p>{{ $product->long_description }}</p>
        </div>
    @endif

    <div class="mb-4">
        <strong>Prix :</strong> {{ $product->price }} €
    </div>

    <div class="mb-4">
        <strong>Stock :</strong> {{ $product->stock }}
    </div>

    <div class="mb-4">
        <strong>Catégorie :</strong> {{ $product->category->name ?? 'Non défini' }}
    </div>

    @if ($product->image)
        <div class="mb-4">
            <img src="{{ asset('storage/' . $product->image) }}" alt="Image du produit" class="w-48 rounded shadow">
        </div>
    @endif

    <a href="{{ route('admin.products.edit', $product) }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded">Modifier</a>
</div>
@endsection
