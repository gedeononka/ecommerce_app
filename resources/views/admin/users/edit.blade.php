@extends('layouts.app')

@section('title', 'Modifier utilisateur')

@section('content')
<div class="p-6 bg-white rounded shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Modifier l’utilisateur</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nom -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Nom</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                class="w-full border border-gray-300 rounded px-3 py-2">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                class="w-full border border-gray-300 rounded px-3 py-2">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Rôles -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Rôles</label>
            @foreach($roles as $role)
                <div class="flex items-center mb-2">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        {{ $user->hasRole($role->name) ? 'checked' : '' }}
                        class="mr-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <label>{{ ucfirst($role->name) }}</label>
                </div>
            @endforeach
            @error('roles')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:underline mr-4">Annuler</a>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
