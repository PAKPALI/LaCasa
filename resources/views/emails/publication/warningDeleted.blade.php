<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Avertissement avant suppression de votre publication</title>
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
                Votre <strong>publication</strong> portant le code :
                <span style="color: #00b98e; font-weight: bold;">{{ $code }}</span>
                est actuellement <strong>désactivée</strong> depuis plusieurs semaines.
            </p>

            <p>
                ⚠️ <strong>Attention :</strong> si aucune action n’est effectuée,
                cette publication sera <strong>supprimée automatiquement dans 5 jours</strong>
                conformément à notre politique de gestion des contenus inactifs.
            </p>

            <p>
                Si votre bien est toujours d’actualité, vous pouvez la <strong>réactiver dès maintenant</strong>
                depuis votre espace personnel pour éviter sa suppression définitive.
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}"
                    style="background-color: #00b98e; color: white; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold;">
                    Réactiver ma publication
                </a>
            </div>

            <p style="color: red; font-weight: bold;">
                ⚠️ Après suppression, il ne sera plus possible de récupérer cette publication.
            </p>

            <p style="margin-top: 30px;">
                Merci de votre confiance,<br>
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
