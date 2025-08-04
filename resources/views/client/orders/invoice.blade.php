<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .invoice-box {
            max-width: 1000px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .invoice-header h1 {
            font-size: 28px;
            margin: 0;
        }

        .company-details, .client-details {
            margin-bottom: 20px;
        }

        .client-details p, .company-details p {
            margin: 0;
        }

        .details {
            width: 100%;
            margin-bottom: 20px;
        }

        .details th, .details td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .details th {
            background-color: #f5f5f5;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 40px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <h1>🧾 Facture</h1>
            <p>Commande #{{ $order->id }}</p>
        </div>

        <!-- Informations vendeur -->
        <div class="company-details">
            <strong>Vendeur :</strong>
            <p>Eshop</p>
            <p>Ouakam,Mérina</p>
            <p>Email : Eshop@gmail.com</p>
        </div>

        <!-- Informations client -->
        <div class="client-details">
            <strong>Client :</strong>
            <p>{{ $order->user->name }}</p>
            <p>{{ $order->address }}</p>
            <p>Téléphone : {{ $order->phone }}</p>
            <p>Email : {{ $order->user->email }}</p>
        </div>

        <!-- Détails commande -->
        <table class="details">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="total">
            <p><strong>Total TTC :</strong> {{ number_format($order->total_amount, 2, ',', ' ') }} €</p>
            <p><strong>Statut paiement :</strong> {{ ucfirst($order->payment_status) }}</p>
            <p><strong>Mode de paiement :</strong> {{ $order->payment_method }}</p>
            <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            Facture générée automatiquement – merci de votre confiance ❤️
        </div>
    </div>
</body>
</html>
