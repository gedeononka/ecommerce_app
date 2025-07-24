<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            🧾 Finaliser ma commande
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto">
        
        <form action="{{ route('orders.store') }}" method="POST" class="bg-white dark:bg-gray-800 shadow-md rounded px-8 pt-6 pb-8 mb-4" x-data="{ paymentMethod: 'Paiement avant livraison' }">
            @csrf

            <!-- Adresse de livraison -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="address">
                    Adresse de livraison
                </label>
                <textarea name="address" id="address" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>

            <!-- Coordonnées -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="phone">
                    Numéro de téléphone
                </label>
                <input type="text" name="phone" id="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <!-- Mode de paiement -->
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="payment_method">
                    Mode de paiement
                </label>
                <select name="payment_method" id="payment_method" 
                        x-model="paymentMethod"
                        class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm" required>
                    <option value="Paiement avant livraison">💳 Paiement avant livraison</option>
                    <option value="Paiement après livraison">💵 Paiement après livraison</option>
                </select>
            </div>
            <!-- Champ de saisie conditionnel -->
<div class="mb-4" id="account_verification" style="display: none;">
    <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2" for="account_number">
        Numéro de compte ou preuve de paiement
    </label>
    <input type="text" name="account_number" id="account_number"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline"
        placeholder="Entrez un numéro de compte ou un ID de transaction">
</div>


            <!-- Bouton de validation -->
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-white hover:bg-gray-50 text-gray-800 px-4 py-2 rounded text-sm font-bold border-2 border-indigo-600 hover:border-indigo-700">
                    ✅ Confirmer la commande
                </button>
            </div>
            <script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethod = document.getElementById('payment_method');
        const accountField = document.getElementById('account_verification');

        function toggleAccountField() {
            if (paymentMethod.value === 'Paiement avant livraison') {
                accountField.style.display = 'block';
            } else {
                accountField.style.display = 'none';
            }
        }

        paymentMethod.addEventListener('change', toggleAccountField);
        toggleAccountField(); // pour initialiser au chargement
    });
</script>

        </form>
    </div>
</x-app-layout>
