<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Nouvelle demande de certification — {{ config('app.name') }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f7f9fb; padding: 40px;">

    <div style="max-width: 700px; margin: auto; background: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden;">

        <!-- Header -->
        <div style="background-color: #0e2e50; color: #ffffff; text-align: center; padding: 20px 0;">
            <h2 style="margin: 0; font-size: 20px;">{{ config('app.name') }}</h2>
        </div>

        <!-- Body -->
        <div style="padding: 28px; color: #0e2e50; line-height: 1.6;">
            <p style="margin:0 0 12px 0;">Bonjour l’équipe LaCasa,</p>

            <p style="margin:0 0 12px 0;">
                Une nouvelle demande de certification a été soumise par l’agence :
                <strong style="color:#0e2e50;">{{ $agency_name }}</strong>.
            </p>

            <p style="margin:0 0 12px 0;">
                Merci de vérifier les éléments fournis et de procéder à la validation si tout est conforme.
            </p>

            <div style="background-color: #f0f4f8; border-radius: 8px; padding: 14px; margin: 16px 0;">
                <p style="margin:0;"><strong>Agence :</strong> {{ $agency_name }}</p>
                <p style="margin:6px 0 0 0;"><strong>Date :</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
            </div>

            <p style="margin: 0 0 18px 0;">
                Pour consulter la demande et prendre les actions nécessaires, rendez-vous dans l’espace administrateur :
            </p>

            <div style="text-align: center; margin: 18px 0;">
                <a href="{{ config('app.url') }}/admin/certifications"
                   style="background-color: #00b98e; color: white; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; display:inline-block;">
                   Voir la demande
                </a>
            </div>

            <p style="margin-top: 10px;">
                Merci pour votre réactivité,<br>
                <strong>L’équipe {{ config('app.name') }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #0e2e50; color: #ffffff; text-align: center; padding: 14px; font-size: 13px;">
            &copy; {{ date('Y') }} {{ config('app.name') }} — Tous droits réservés.
            <div style="margin-top:8px; color:#00b98e; font-weight:600;">
                LaCasa - Bouger simplement
            </div>
        </div>
    </div>

</body>
</html>
