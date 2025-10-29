<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Publication supprimée</title>
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
                <strong>supprimée automatiquement</strong> de notre plateforme.
            </p>

            <p>
                Cette suppression intervient suite à une période d’inactivité prolongée,
                dépassant le délai maximal de conservation autorisé après désactivation.
            </p>

            <p>
                Si vous souhaitez republier cette annonce, vous pouvez le faire facilement depuis votre espace personnel
                en créant une nouvelle publication via le lien ci-dessous :
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}"
                    style="background-color: #00b98e; color: white; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold;">
                    Accéder à mon compte
                </a>
            </div>

            <p style="color: red; font-weight: bold;">
                ⚠️ Note : Les publications supprimées ne peuvent plus être récupérées.
                Nous vous recommandons de republier votre bien si celui-ci est toujours disponible.
            </p>

            <p style="margin-top: 30px;">Merci de votre compréhension,<br>
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
