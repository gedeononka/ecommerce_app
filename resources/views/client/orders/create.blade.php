<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            🧾 Finaliser ma commande
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto">

        <!-- FORMULAIRE COMMUN AVEC CHANGEMENT D’ACTION SELON LE MODE DE PAIEMENT -->
        <form 
            method="POST" 
            :action="paymentMethod === 'Paiement avant livraison' ? '{{ route('stripe.checkout') }}' : '{{ route('orders.store') }}'"
            x-data="{ paymentMethod: 'Paiement avant livraison' }"
            class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4"
        >
            @csrf

            <!-- Adresse de livraison -->
            <div class="mb-4">
                <label for="address" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">
                    Adresse de livraison
                </label>
                <textarea name="address" id="address" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>

            <!-- Téléphone -->
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">
                    Numéro de téléphone
                </label>
                <input type="text" name="phone" id="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <!-- Mode de paiement -->
            <div class="mb-4">
                <label for="payment_method" class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">
                    Mode de paiement
                </label>
                <select name="payment_method" id="payment_method" x-model="paymentMethod" class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm" required>
                    <option value="Paiement avant livraison">💳 Paiement avant livraison</option>
                    <option value="Paiement après livraison">💵 Paiement après livraison</option>
                </select>
            </div>

            <!-- Message explicatif selon le paiement -->
            <div class="mb-4" x-show="paymentMethod === 'Paiement avant livraison'">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    🔐 Vous serez redirigé vers une page de paiement sécurisée (Stripe).
                </p>
            </div>
            <div class="mb-4" x-show="paymentMethod === 'Paiement après livraison'">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    📦 Vous paierez à la livraison. Aucun paiement immédiat requis.
                </p>
            </div>

            <!-- Bouton de confirmation -->
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                    ✅ Confirmer la commande
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
