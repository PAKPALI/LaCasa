<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Facture Paiement - {{ config('app.name') }}</title>
        <style>
            body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; }
            .container { width: 90%; margin: auto; padding: 20px; }
            .header { background: #0e2e50; color: #fff; padding: 20px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; }
            .details, .items { margin-top: 30px; }
            .details p { margin: 5px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            table, th, td { border: 1px solid #ddd; }
            th, td { padding: 12px; text-align: left; }
            th { background-color: #00b88d; color: #fff; }
            .total { margin-top: 20px; text-align: right; font-size: 18px; font-weight: bold; color:green;}
            .footer { background: #0e2e50; margin-top: 50px; text-align: center; font-size: 12px; color: white; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Facture Paiement</h1>
            </div>

            <div class="details">
                <p><strong>Client :</strong> {{ $user->name }}</p>
                <p><strong>Email :</strong> {{ $user->email }}</p>
                <p><strong>Date :</strong> {{ now()->format('d/m/Y H:i') }}</p>
                <p><strong>ID Transaction :</strong> {{ $data['transaction_id'] }}</p>
                <p><strong>Référence :</strong> {{ $data['reference'] ?? 'N/A' }}</p>
                <p><strong>Méthode de paiement :</strong> {{ $data['payment_method'] ?? 'N/A' }}</p>
                <p><strong>Opérateur :</strong> {{ $data['payment_operator'] ?? 'N/A' }}</p>
            </div>

            <div class="items">
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Montant</th>
                            <th>Devise</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Paiement LaCasa</td>
                            <td>{{ $data['amount'] ?? 'N/A' }}</td>
                            <td>{{ $data['currency'] ?? 'XOF' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="total">
                Total : {{ $data['amount'] ?? 'N/A' }} {{ $data['currency'] ?? 'XOF' }}
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} {{ config('app.name') }} — Tous droits réservés.<br>
                LaCasa - Bouger simplement
            </div>
        </div>
    </body>
</html>