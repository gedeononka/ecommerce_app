@extends('layouts.app')

@section('title', 'Liste des catégories')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">📂 Liste des catégories</h1>
        <a href="{{ route('admin.categories.create') }}"
           class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
            ➕ Nouvelle catégorie
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($categories->count())
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">Nom</th>
                        <th class="px-4 py-2 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $category->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $category->name }}</td>
                            <td class="px-4 py-2 border-b text-center space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-blue-600 hover:underline text-sm">✏️ Modifier</a>

                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Confirmer la suppression de cette catégorie ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">🗑️ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    @else
        <p class="text-gray-600">Aucune catégorie trouvée.</p>
    @endif
</div>
@endsection
