<!DOCTYPE html>
<html lang="fr">
  <head><meta charset="utf-8"></head>
  <style>
      .logo { width: 120px; margin-bottom: 15px; }
      .header { text-align: center; margin-bottom: 20px; }
      .content { font-family: Arial, sans-serif; color: #0e2e50; }
  </style>
  
  <body style="font-family: Arial, sans-serif; background:#f7f9fb; padding:30px">
    <div style="max-width:700px;margin:auto;background:#fff;border-radius:8px;overflow:hidden;">
      <div style="background:#0e2e50;color:#fff;padding:18px;text-align:center;">
        <h3 style="margin:0">{{ config('app.name') }}</h3>
        <!-- <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="logo" /> -->
      </div>

      <div style="padding:20px;color:#0e2e50;">
        <p>Bonjour {{ $review->user->name }},</p>

        <p>Votre avis publié le <strong>{{ $review->created_at->format('d/m/Y H:i') }}</strong> a reçu une réponse de la part de <strong>{{ $userName }}</strong>.</p>

        <div style="background:#f0f4f8;padding:12px;border-radius:6px;margin:12px 0;">
          <p style="margin:0;"><strong>Votre avis :</strong></p>
          <p style="margin:6px 0 0 0;">{{ Str::limit($review->comment, 1000) }}</p>
          <hr style="margin:10px 0;">
          <p style="margin:0;"><strong>Réponse :</strong></p>
          <p style="margin:6px 0 0 0;">{{ Str::limit($comment->comment, 2000) }}</p>
        </div>

        <p>Vous pouvez revenir sur la plateforme pour poursuivre la conversation ou modifier votre avis si nécessaire :</p>

        <div style="text-align:center;margin-top:14px;">
          <a href="{{ config('app.url') }}/reviews/{{ $review->id }}" style="background:#00b98e;color:#fff;padding:10px 18px;border-radius:6px;text-decoration:none;font-weight:700;">
            Voir l'avis
          </a>
        </div>

        <p style="margin-top:14px;">Merci de votre participation,<br><strong>{{ config('app.name') }}</strong></p>
      </div>

      <div style="background:#0e2e50;color:#fff;padding:12px;text-align:center;font-size:12px;">
        &copy; {{ date('Y') }} {{ config('app.name') }}
      </div>
    </div>
  </body>
</html>
