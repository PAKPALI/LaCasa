<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur {{ config('app.name') }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f7f9fb; padding: 40px;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">

        <!-- Header -->
        <div style="background-color: #0e2e50; color: #ffffff; text-align: center; padding: 20px 0;">
            <h2 style="margin: 0;">{{ config('app.name') }}</h2>
        </div>

        <!-- Body -->
        <div style="padding: 30px; color: #0e2e50;">
            <p>Bonjour <strong>{{ $user_name }}</strong>,</p>

            <p>
                🎉 <strong>Bienvenue sur {{ config('app.name') }} !</strong>  
                Nous sommes ravis de vous compter parmi nos utilisateurs.
            </p>

            <p>
                Votre compte a été créé avec succès. Voici vos identifiants personnels :
            </p>

            <div style="background-color: #f0f4f8; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <p><strong>Nom d'utilisateur :</strong> {{ $user_name }}</p>
                <p><strong>Email :</strong> {{ $email }}</p>
                <p><strong>Mot de passe :</strong> {{ $password }}</p>
            </div>

            <p>
                Vous pouvez dès maintenant vous connecter à votre compte en cliquant sur le bouton ci-dessous :
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}"
                    style="background-color: #00b98e; color: white; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold;">
                    Accéder à mon compte
                </a>
            </div>

            <p>
                Si vous rencontrez des difficultés pour vous connecter ou souhaitez modifier vos informations, 
                n’hésitez pas à contacter notre équipe de support.
            </p>

            <p style="margin-top: 30px;">Merci de votre confiance,<br>
                <strong>L’équipe {{ config('app.name') }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #0e2e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;">
            &copy; {{ date('Y') }} {{ config('app.name') }} — Tous droits réservés.
            <div style="background-color: #0e2e50; color: #00b98e; text-align: center; padding: 15px; font-size: 14px;">
                LaCasa - Bouger simplement
            </div>
        </div>
    </div>

</body>

</html>
