@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Mes Commandes</h1>
        <div class="text-sm text-gray-600">
    {{ $orders->count() }} commande(s) au total
</div>

<a href="{{ route('orders.create') }}"
          class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
            ➕ Nouvelle commande
        </a>
       <a href="{{ route('orders.index') }}" class="bg-white hover:bg-gray-50 text-gray-800 px-3 py-1.5 rounded text-xs font-bold border-2 border-indigo-600 hover:border-indigo-300">
    Réinitialiser
</a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('orders.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="en attente" {{ request('status') == 'en attente' ? 'selected' : '' }}>En attente</option>
                    <option value="expédiée" {{ request('status') == 'expédiée' ? 'selected' : '' }}>Expédiée</option>
                    <option value="livrée" {{ request('status') == 'livrée' ? 'selected' : '' }}>Livrée</option>
                    <option value="annulée" {{ request('status') == 'annulée' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                <select name="period" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toute période</option>
                    <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>7 derniers jours</option>
                    <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>30 derniers jours</option>
                    <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>3 derniers mois</option>
                    <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>

            <div class="flex gap-2">
               <button type="submit" class="bg-white hover:bg-gray-50 text-gray-800 px-3 py-1.5 rounded text-xs font-bold border-2 border-indigo-600 hover:border-indigo-300">
                    Filtrer
            </button>

            </div>
        </form>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                            <!-- Informations de la commande -->
                            <div class="flex-1">
                                <div class="flex items-center space-x-4 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-800">
    <a href="{{ route('orders.show', $order) }}" class="hover:underline text-blue-600">
        🧾 Commande #{{ $order->order_number }}
    </a>
</h3>

                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @switch($order->status)
                                            @case('en attente') bg-yellow-100 text-yellow-800 @break
                                            @case('expédiée') bg-purple-100 text-purple-800 @break
                                            @case('livrée') bg-green-100 text-green-800 @break
                                            @case('annulée') bg-red-100 text-red-800 @break
                                        @endswitch
                                    ">
                                        @switch($order->status)
                                            @case('en attente') En attente @break
                                            @case('expédiée') Expédiée @break
                                            @case('livrée') Livrée @break
                                            @case('annulée') Annulée @break
                                        @endswitch
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600 space-y-1">
                                    <p><span class="font-medium">Date:</span> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                    <p><span class="font-medium">Articles:</span> {{ $order->items->sum('quantity') }} article(s)</p>
                                    <p><span class="font-medium">Mode de paiement:</span> 
                                        @if ($order->payment_method === 'Paiement avant livraison')
                                          💳 {{ $order->payment_method }}
                                             @else
                                         📦 {{ $order->payment_method }}
                                        @endif

                                    </p>
                                    @if($order->tracking_number)
                                        <p><span class="font-medium">Suivi:</span> {{ $order->tracking_number }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Prix et actions -->
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-800 mb-4">
                                    {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                      class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
            
                                        Voir détails
                                    </a>

                                    @if($order->status == 'livrée' && $order->invoice_path)
                                        <a href="{{ route('orders.invoice', $order->id) }}" 
                                         class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                                    
                                            Télécharger facture
                                        </a>
                                    @endif

                                    @if(in_array($order->status, ['en attente']))
                                        <button onclick="cancelOrder({{ $order->id }})" 
                                               class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                                            Annuler
                                        </button>
                                    @endif

                                    @if($order->status == 'livrée')
                                        <a href="{{ route('orders.reorder', $order) }}">Recommander</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu des articles -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"> 
    @foreach($order->items->take(4) as $item)
        <div class="flex items-center space-x-3">
            @if($item->product->image)
                <img src="{{ asset('storage/' . $item->product->image) }}" 
                     alt="{{ $item->product->name }}"
                     class="w-16 h-16 object-cover rounded-lg shadow-sm">
            @else
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                    </svg>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $item->product->name }}</p>
                <p class="text-xs text-gray-600">Qté: {{ $item->quantity }}</p>
            </div>
        </div>

                                @endforeach
                                @if($order->items->count() > 4)
                                    <div class="flex items-center justify-center text-sm text-gray-500">
                                        +{{ $order->items->count() - 4 }} autres
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->appends(request()->query())->links() }}
        </div>

    @else
        <!-- Aucune commande -->
        <div class="text-center py-16">
            <div class="mx-auto w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Aucune commande trouvée</h2>
            <p class="text-gray-600 mb-8">
                @if(request('status') || request('period'))
                    Aucune commande ne correspond aux filtres sélectionnés.
                @else
                    Vous n'avez pas encore passé de commande.
                @endif
            </p>
            @if(!request('status') && !request('period'))
                <a href="{{ route('products.index') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition-colors inline-block">
                    Découvrir nos produits
                </a>
            @else
                <a href="{{ route('orders.index') }}" 
                  class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                                    
                    Voir toutes mes commandes
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Modal de confirmation d'annulation -->
<div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Annuler la commande</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmCancel" class="text-lg font-medium text-gray-900">
                    Oui, annuler
                </button>
                <button id="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Non, garder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let orderToCancel = null;

function cancelOrder(orderId) {
    orderToCancel = orderId;
    document.getElementById('cancelModal').classList.remove('hidden');
}

document.getElementById('confirmCancel').onclick = function() {
    if (orderToCancel) {
        // Créer un formulaire pour envoyer la requête de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/orders/${orderToCancel}/cancel`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PATCH';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
    document.getElementById('cancelModal').classList.add('hidden');
};

document.getElementById('closeModal').onclick = function() {
    document.getElementById('cancelModal').classList.add('hidden');
    orderToCancel = null;
};

// Fermer le modal en cliquant à l'extérieur
document.getElementById('cancelModal').onclick = function(event) {
    if (event.target === this) {
        this.classList.add('hidden');
        orderToCancel = null;
    }
};
</script>
@endsection