<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement réussi - {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f7f9fb; padding: 40px;">
    <div style="max-width:600px;margin:auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background-color:#0e2e50;color:#fff;text-align:center;padding:20px 0;">
            <h2 style="margin:0;">{{ config('app.name') }}</h2>
        </div>

        <!-- Body -->
        <div style="padding:30px;color:#0e2e50;">
            <p>Bonjour <strong>{{ $user_name }}</strong>,</p>

            <p>✅ Votre paiement a été effectué avec succès !</p>

            <p>Détails de votre transaction :</p>
            <ul>
                <li><strong>Montant :</strong> {{ $data['amount'] ?? 'N/A' }} {{ $data['currency'] ?? 'XOF' }}</li>
                <li><strong>Référence :</strong> {{ $data['reference'] ?? 'N/A' }}</li>
                <li><strong>Méthode de paiement :</strong> {{ $data['payment_method'] ?? 'N/A' }}</li>
                <li><strong>Opérateur :</strong> {{ $data['payment_operator'] ?? 'N/A' }}</li>
                <li><strong>ID Transaction :</strong> {{ $data['transaction_id'] ?? 'N/A' }}</li>
            </ul>

            <p>Vous trouverez ci-joint la facture au format PDF.</p>

            <p style="margin-top:30px;">L’équipe {{ config('app.name') }}</p>
        </div>

        <!-- Footer -->
        <div style="background-color:#0e2e50;color:#fff;text-align:center;padding:15px;font-size:14px;">
            &copy; {{ date('Y') }} {{ config('app.name') }} — Tous droits réservés.
            <div style="color:#00b98e;">LaCasa - Bouger simplement</div>
        </div>
    </div>
</body>
</html>
