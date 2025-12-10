<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation de votre mot de passe</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f7f9fb; padding: 40px;">

<div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">

    <!-- Header -->
    <div style="background-color: #0e2e50; color: #ffffff; text-align: center; padding: 20px 0;">
        <h2 style="margin: 0;">{{ config('app.name') }}</h2>
    </div>

    <!-- Body -->
    <div style="padding: 30px; color: #0e2e50;">

        <p>Bonjour <strong>{{ $user_name }}</strong>,</p>

        <p>
            Vous avez demandé à réinitialiser votre mot de passe.  
            Un nouveau mot de passe a été généré pour vous :
        </p>

        <div style="background-color: #f0f4f8; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <p><strong>Nouveau mot de passe :</strong> {{ $password }}</p>
        </div>

        <p>
            ⚠️ <strong>Nous vous recommandons fortement de modifier ce mot de passe dès votre prochaine connexion</strong>
            afin de garantir la sécurité de votre compte.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}"
               style="background-color: #00b98e; color: white; text-decoration: none; 
                      padding: 12px 25px; border-radius: 6px; font-weight: bold;">
                Me connecter
            </a>
        </div>

        <p>
            En cas de souci, notre équipe reste disponible pour vous assister.
        </p>

        <p style="margin-top: 30px;">
            Merci de votre confiance,<br>
            <strong>L’équipe {{ config('app.name') }}</strong>
        </p>
    </div>

    <!-- Footer -->
    <div style="background-color: #0e2e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;">
        &copy; {{ date('Y') }} {{ config('app.name') }} — Tous droits réservés.
    </div>

</div>

</body>
</html>
