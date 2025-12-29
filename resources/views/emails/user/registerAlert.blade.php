<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau compte utilisateur</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f4f4f4;">
    
    <table align="center" cellpadding="0" cellspacing="0" width="100%" style="padding: 20px 0;">
        <tr>
            <td>
                <table align="center" cellpadding="0" cellspacing="0" width="600" 
                       style="background-color:#ffffff; border-radius:8px; overflow:hidden;">
                    
                    <!-- HEADER -->
                    <tr>
                        <td style="background-color:#0a5275; padding:20px; text-align:center;">
                            <h2 style="color:white; margin:0;">Nouvelle Agence enregistrée</h2>
                        </td>
                    </tr>

                    <!-- CONTENU -->
                    <tr>
                        <td style="padding: 25px; color:#333;">
                            
                            <p style="font-size:16px;">Bonjour cher utilisateur,</p>

                            <p style="font-size:15px; line-height:1.6;">
                                Une nouvelle agence vient de s’inscrire sur <strong style="color:#00b98e; font-weight: bold;">LaCasa</strong>.
                                Voici les informations concernant ce compte :
                            </p>

                            <table width="100%" cellpadding="8" cellspacing="0"
                                   style="background:#f7f7f7; border-radius:6px; margin:15px 0;">
                                <tr>
                                    <td><strong>Nom :</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email :</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <!-- <tr>
                                    <td><strong>Type d’utilisateur :</strong></td>
                                    <td>
                                        @if($user->user_type == 1)
                                            Personne / Client
                                        @else
                                            Agence
                                        @endif
                                    </td>
                                </tr> -->
                                <tr>
                                    <td><strong>Date d'inscription :</strong></td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>

                            <h3 style="color:#0a5275;">Statistiques globales</h3>

                            <table width="100%" cellpadding="8" cellspacing="0"
                                   style="background:#eef9ff; border-radius:6px;">
                                <tr>
                                    <td><strong>Total utilisateurs :</strong></td>
                                    <td>{{ $stats['total_users'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total agences :</strong></td>
                                    <td>{{ $stats['total_agencies'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total clients / personnes :</strong></td>
                                    <td>{{ $stats['total_persons'] }}</td>
                                </tr>
                            </table>

                            <p style="margin-top:20px; font-size:15px; line-height:1.6;">
                                Connectez-vous pour consulter de nouvelles annonces.
                            </p>
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ config('app.url') }}"
                                    style="background-color: #00b98e; color: white; text-decoration: none; padding: 12px 25px; border-radius: 6px; font-weight: bold;">
                                    Accéder à LaCasa
                                </a>
                            </div>

                            <p style="font-size:14px; color:#666;">— L’équipe LaCasa</p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color:#0a5275; text-align:center; padding:15px;">
                            <p style="color:white; margin:0; font-size:13px;">
                                © {{ date('Y') }} LaCasa — Tous droits réservés.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
