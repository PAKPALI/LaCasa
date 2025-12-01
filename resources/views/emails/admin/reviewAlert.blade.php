<!DOCTYPE html>
<html lang="fr">
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background:#f7f9fb; padding:30px">
  <div style="max-width:700px;margin:auto;background:#fff;border-radius:8px;overflow:hidden;">
    <div style="background:#0e2e50;color:#fff;padding:18px;text-align:center;">
      <h3 style="margin:0">{{ config('app.name') }}</h3>
    </div>

    <div style="padding:20px;color:#0e2e50;">
      <p>Bonjour,</p>
      <p>Un nouvel avis a été déposé sur la plateforme par <strong>{{ $author->name }}</strong>.</p>

      <div style="background:#f0f4f8;padding:12px;border-radius:6px;margin:12px 0;">
        <p style="margin:0;"><strong>Note :</strong> {{ $review->rating }} / 5</p>
        <p style="margin:8px 0 0 0;"><strong>Avis :</strong></p>
        <p style="margin:4px 0 0 0;">{{ Str::limit($review->comment, 1000) }}</p>
        <p style="margin-top:8px;"><strong>Date :</strong> {{ $review->created_at->format('d/m/Y H:i') }}</p>
      </div>

      <p>Pour modérer ou répondre, connectez-vous au panneau d'administration.</p>

      <div style="text-align:center;margin-top:14px;">
        <a href="{{ config('app.url') }}/admin/reviews" style="background:#00b98e;color:#fff;padding:10px 18px;border-radius:6px;text-decoration:none;font-weight:700;">
          Voir l'avis
        </a>
      </div>

      <p style="margin-top:14px;">Merci,<br><strong>{{ config('app.name') }}</strong></p>
    </div>

    <div style="background:#0e2e50;color:#fff;padding:12px;text-align:center;font-size:12px;">
      &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
  </div>
</body>
</html>
