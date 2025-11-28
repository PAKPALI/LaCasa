<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Facture</title>
        <style>
            body { font-family: Arial, sans-serif; color: #333; }
            .header { text-align: center; padding-bottom: 20px; }
            .invoice-box { width: 100%; padding: 30px; border: 1px solid #eee; border-radius: 10px; }
            .details { margin-bottom: 20px; }
            .total { font-size: 18px; font-weight: bold; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="invoice-box">
            <div class="header">
                <h2>{{ config('app.name') }}</h2>
                <h3>Facture</h3>
            </div>

            <div class="details">
                <p><strong>Nom :</strong> {{ $user->name }}</p>
                <p><strong>Email :</strong> {{ $user->email }}</p>
                <p><strong>Date :</strong> {{ now()->format('d/m/Y H:i') }}</p>
                <p><strong>Transaction ID :</strong> {{ $payment['transaction_id'] }}</p>
            </div>

            <div class="total">
                Montant payé : {{ $payment['amount'] }} {{ $payment['currency'] }}
            </div>
        </div>
    </body>
</html>
