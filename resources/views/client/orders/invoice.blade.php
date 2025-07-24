<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Facture #{{ $order->id }}</h2>
    <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Client :</strong> {{ $order->user->name }}<br>
       <strong>Téléphone :</strong> {{ $order->phone }}<br>
       <strong>Adresse :</strong> {{ $order->address }}</p>
    <p><strong>Mode de paiement :</strong> @if ($order->payment_method === 'Paiement avant livraison')
     {{ $order->payment_method }}
@else
     {{ $order->payment_method }}
@endif
</p>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Total TTC</strong></td>
                <td><strong>{{ number_format($order->total_amount, 2, ',', ' ') }} FCFA</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
