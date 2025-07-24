@extends('layouts.app')

@section('title', 'Liste des utilisateurs')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Liste des utilisateurs</h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($users->count())
    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Nom</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Rôles</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    {{ $user->getRoleNames()->join(', ') ?: '—' }}
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline mr-2">Voir</a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-green-600 hover:underline">Modifier</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
    @else
    <p>Aucun utilisateur trouvé.</p>
    @endif
</div>
@endsection
