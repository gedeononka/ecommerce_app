<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Message de bienvenue assombri et doux --}}
            <div class="bg-gray-900 text-gray-200 border border-gray-800 shadow-sm rounded-lg p-6">
                <h1 class="text-xl font-semibold">
                    👋 Bienvenue M. {{ Auth::user()->name }}
                </h1>
                <p class="mt-2 text-sm text-gray-400">
                    Heureux de vous revoir dans votre espace.
                </p>
            </div>

            {{-- Zone d’actions --}}
            <div class="bg-gray-900 text-gray-200 border border-gray-800 shadow-sm rounded-lg p-6">
                @if(auth()->user()->hasRole('admin'))
                    <h3 class="text-lg font-semibold mb-4 text-blue-300">🎯 Actions administrateur</h3>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.products.index') }}"
                           class="transition hover:brightness-110 bg-blue-900 text-gray-100 px-4 py-2 rounded-md shadow-sm flex items-center gap-2">
                            🛍️ Gérer les produits
                        </a>
                        <a href="{{ route('admin.categories.index') }}"
                           class="transition hover:brightness-110 bg-green-900 text-gray-100 px-4 py-2 rounded-md shadow-sm flex items-center gap-2">
                            📂 Gérer les catégories
                        </a>
                        <a href="{{ url('orders') }}"
                           class="transition hover:brightness-110 bg-yellow-900 text-gray-100 px-4 py-2 rounded-md shadow-sm flex items-center gap-2">
                            📦 Gérer les commandes
                        </a>
                        <a href="{{ url('users') }}"
                           class="transition hover:brightness-110 bg-purple-900 text-gray-100 px-4 py-2 rounded-md shadow-sm flex items-center gap-2">
                            👥 Liste des utilisateurs
                        </a>
                        <a href="{{ url('stats') }}"
                           class="transition hover:brightness-110 bg-pink-900 text-gray-100 px-4 py-2 rounded-md shadow-sm flex items-center gap-2">
                            📊 Voir les statistiques
                        </a>
                    </div>
                @elseif(auth()->user()->hasRole('client'))
                    <h3 class="text-lg font-semibold mb-4 text-green-300">🛒 Zone Client</h3>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                         <a href="{{ route('products.index') }}"
                           class="transition hover:brightness-110 bg-indigo-900 text-gray-100 px-4 py-2 rounded-md shadow-sm flex items-center gap-2">
                            🛍️ Voir les produits
                        </a>
                         <a href="{{ route('cart.index') }}"
                           class="transition hover:brightness-110 bg-teal-900 text-gray-100 px-4 py-2 rounded-md shadow-sm flex items-center gap-2">
                            🧺 Mon panier
                        </a>
                         <a href="{{ route('orders.index') }}"
                           class="transition hover:brightness-110 bg-orange-900 text-gray-100 px-4 py-2 rounded-md shadow-sm flex items-center gap-2">
                            📃 Mes commandes
                        </a>
                    </div>
                @else
                    <p class="text-yellow-400 font-semibold">⚠️ Aucun rôle attribué à cet utilisateur.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
