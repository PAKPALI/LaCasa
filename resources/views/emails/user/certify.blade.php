<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Certification réussie sur {{ config('app.name') }}</title>
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
                🎉 <strong>Félicitations !</strong>  
                Votre compte a été certifié avec succès par notre équipe.
            </p>

            <p>
                Vous faites désormais partie de la liste officielle de nos partenaires, visible dans la section d’accueil de notre plateforme.  
                Cette certification augmente votre crédibilité auprès des clients qui visitent notre site.
            </p>

            <p>
                Pour profiter pleinement de votre statut de partenaire certifié, vous pouvez consulter votre profil et vos informations via le bouton ci-dessous :
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ config('app.url') }}"
                    style="background-color: #00b98e; color: white; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold;">
                    Voir mon profil partenaire
                </a>
            </div>

            <p>
                Nous vous encourageons à maintenir vos informations sociales à jour pour que vos clients puissent bénéficier d’une expérience optimale.
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