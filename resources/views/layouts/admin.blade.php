<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title') - Admin</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-gray-200 min-h-screen flex flex-col">
        <div class="px-6 py-4 text-2xl font-bold border-b border-gray-800">
            Admin Panel
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800' : '' }}">
               🏠 Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-gray-800' : '' }}">
               🛍️ Produits
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800' : '' }}">
               📂 Catégories
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800' : '' }}">
               📦 Commandes
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800' : '' }}">
               👥 Utilisateurs
            </a>
            <a href="{{ route('admin.stats.index') }}"
               class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.stats.*') ? 'bg-gray-800' : '' }}">
               📊 Statistiques
            </a>
        </nav>

        <div class="px-6 py-4 border-t border-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700">
                    🔒 Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 p-8 overflow-auto">
        <header class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">@yield('title')</h1>
            <div class="text-gray-600">
                Bonjour, {{ Auth::user()->name }}
            </div>
        </header>

        <section>
            @yield('content')
        </section>
    </main>

</body>
</html>
