<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Demande de certification rejetée sur {{ config('app.name') }}</title>
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
                ❌ <strong>Votre demande de certification a été rejetée.</strong>
            </p>

            <p>
                <p>Voici les points qui n'ont pas été validés :</p>
                <div style="background-color: #f8d7da; color: #842029; padding: 15px; border-radius: 6px; margin-top: 10px;">
                    {!! '- ' . str_replace("\n", "<br> ", $motifs) !!}
                </div>
            </p>

            @if(!empty($comment))
            <p>
                Commentaire de notre équipe :  
                <div style="background-color: #fff3cd; color: #664d03; padding: 15px; border-radius: 6px; margin-top: 10px;">
                    {{ $comment }}
                </div>
            </p>
            @endif

            <p>
                Merci de corriger ces points et de soumettre à nouveau votre demande pour obtenir la certification.
            </p>

            <p style="margin-top: 30px;">
                L’équipe {{ config('app.name') }}
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
