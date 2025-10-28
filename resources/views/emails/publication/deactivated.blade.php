<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Publication désactivée</title>
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
                Nous vous informons que votre <strong>publication</strong> ayant pour code :
                <span style="color: #00b98e; font-weight: bold;">{{ $code }}</span> a été
                <strong>désactivée automatiquement</strong> car elle a atteint la durée de validité de 30 jours.
            </p>

            <p>
                Pour la réactiver, veuillez vous connecter à votre compte sur la plateforme
                en cliquant sur le lien ci-dessous :
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}" style="background-color: #00b98e; color: white; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold;">
                    Se connecter à mon compte
                </a>
            </div>

            <p>
                Une fois connecté, vous pouvez réactiver votre publication depuis votre tableau de bord.
            </p>

            <p style="color: red; font-weight: bold;">
                ⚠️ Note importante : si cette publication n’est pas réactivée avant le prochain mois,
                elle sera automatiquement <u>supprimée</u> afin de libérer de l’espace sur nos serveurs.
            </p>

            <p style="margin-top: 30px;">Merci de votre confiance,<br>
            <strong>L’équipe {{ config('app.name') }}</strong></p>
        </div>

        <!-- Footer -->
        <div style="background-color: #0e2e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;">
            &copy; {{ date('Y') }} {{ config('app.name') }} — Tous droits réservés.
        </div>
    </div>
</body>
</html>
