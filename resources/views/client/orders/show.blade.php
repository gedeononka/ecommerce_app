<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            📦 Détails de la commande #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto">
        <!-- Informations sur la commande -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">🧾 Informations</h3>

            <p><strong>Adresse de livraison :</strong> {{ $order->address }}</p>
            <p><strong>Téléphone :</strong> {{ $order->phone }}</p>
            <p><strong>Mode de paiement :</strong> 
                @if ($order->payment_method === 'Paiement avant livraison')
    💳 {{ $order->payment_method }}
@else
    📦 {{ $order->payment_method }}
@endif

            </p>
            <p><strong>Statut de la commande :</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Statut du paiement :</strong> 
                <span class="{{ $order->payment_status === 'payé' ? 'text-green-600 font-semibold' : 'text-red-500 font-semibold' }}">
                    {{ ucfirst($order->payment_status ?? 'non payé') }}
                </span>
            </p>
            <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Produits commandés -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">🛒 Produits commandés</h3>

            <table class="w-full table-auto">
                <thead>
                    <tr class="text-left bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        <th class="px-4 py-2">Produit</th>
                        <th class="px-4 py-2">Prix</th>
                        <th class="px-4 py-2">Quantité</th>
                        <th class="px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-200">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->product->name }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                            <td class="border px-4 py-2">{{ $item->quantity }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total + Facture -->
            @if(Storage::exists('public/invoices/facture_commande_' . $order->id . '.pdf'))
    <a href="{{ asset('storage/invoices/facture_commande_' . $order->id . '.pdf') }}" 
       target="_blank" 
       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
        🧾 Télécharger la facture PDF
    </a>
@endif

        </div>
    </div>
</x-app-layout>
