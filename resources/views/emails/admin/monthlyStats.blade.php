<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Rapport Mensuel - Statistiques Utilisateurs</title>
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
                                <h2 style="color:white; margin:0;">📊 Rapport Mensuel - Utilisateurs</h2>
                            </td>
                        </tr>

                        <!-- CONTENU -->
                        <tr>
                            <td style="padding: 25px; color:#333;">

                                <p style="font-size:16px;">Bonjour cher administrateur,</p>

                                <p style="font-size:15px; line-height:1.6;">
                                    Voici les statistiques des utilisateurs pour le mois écoulé :
                                </p>

                                <!-- Période -->
                                <table width="100%" cellpadding="8" cellspacing="0"
                                    style="background:#f7f7f7; border-radius:6px; margin:15px 0;">
                                    <tr>
                                        <td><strong>Mois actuel :</strong></td>
                                        <td>{{ $stats['period']['current'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mois précédent :</strong></td>
                                        <td>{{ $stats['period']['previous'] }}</td>
                                    </tr>
                                </table>

                                <!-- Stats actuelles -->
                                <h3 style="color:#0a5275;">Statistiques du mois actuel</h3>
                                <table width="100%" cellpadding="8" cellspacing="0"
                                    style="background:#eef9ff; border-radius:6px;">
                                    <tr>
                                        <td><strong>Total utilisateurs :</strong></td>
                                        <td>{{ $stats['current_month']['total_users'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total agences :</strong></td>
                                        <td>{{ $stats['current_month']['total_agency'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total clients / personnes :</strong></td>
                                        <td>{{ $stats['current_month']['total_clients'] }}</td>
                                    </tr>
                                </table>

                                <!-- Stats mois précédent -->
                                <h3 style="color:#0a5275;">Comparaison avec le mois précédent</h3>
                                <table width="100%" cellpadding="8" cellspacing="0"
                                    style="background:#f7f7f7; border-radius:6px;">
                                    <tr>
                                        <td><strong>Différence total utilisateurs :</strong></td>
                                        <td>{{ $stats['diff']['users'] >= 0 ? '+' : '' }}{{ $stats['diff']['users'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Différence total agences :</strong></td>
                                        <td>{{ $stats['diff']['agency'] >= 0 ? '+' : '' }}{{ $stats['diff']['agency'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Différence total clients :</strong></td>
                                        <td>{{ $stats['diff']['clients'] >= 0 ? '+' : '' }}{{ $stats['diff']['clients'] }}</td>
                                    </tr>
                                </table>

                                <p style="margin-top:20px; font-size:15px; line-height:1.6;">
                                    Connectez-vous à la partie admin pour consulter plus de détails.
                                </p>

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