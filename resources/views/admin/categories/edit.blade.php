@extends('layouts.app')

@section('title', 'Modifier une catégorie')

@section('content')
<div class="p-6 bg-white rounded shadow max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Modifier la catégorie</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <strong>Erreurs :</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">Nom de la catégorie</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   value="{{ old('name', $category->name) }}" required>
        </div>

       

        <div class="pt-2">
            <button type="submit" class="text-2xl font-bold mb-4">
                💾 Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
