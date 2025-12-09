<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMonthlyUserStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $now = now();

        // Période du mois actuel
        $start = $now->copy()->startOfMonth();
        $end   = $now->copy()->endOfMonth();

        // Mois précédent
        $prevStart = $now->copy()->subMonth()->startOfMonth();
        $prevEnd   = $now->copy()->subMonth()->endOfMonth();

        // ➤ Données du mois actuel
        $current_total_users   = User::whereBetween('created_at', [$start, $end])->count();
        $current_total_agency  = User::where('user_type', 2)->whereBetween('created_at', [$start, $end])->count();
        $current_total_clients = User::where('user_type', 1)->whereBetween('created_at', [$start, $end])->count();

        // ➤ Données du mois précédent
        $prev_total_users   = User::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $prev_total_agency  = User::where('user_type', 2)->whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $prev_total_clients = User::where('user_type', 1)->whereBetween('created_at', [$prevStart, $prevEnd])->count();

        // ➤ Statistiques envoyées à l’email
        $stats = [
            'period' => [
                'current' => $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y'),
                'previous' => $prevStart->format('d/m/Y') . ' - ' . $prevEnd->format('d/m/Y'),
            ],
            'current_month' => [
                'total_users' => $current_total_users,
                'total_agency' => $current_total_agency,
                'total_clients' => $current_total_clients,
            ],
            'previous_month' => [
                'total_users' => $prev_total_users,
                'total_agency' => $prev_total_agency,
                'total_clients' => $prev_total_clients,
            ],
            'diff' => [
                'users' => $current_total_users - $prev_total_users,
                'agency' => $current_total_agency - $prev_total_agency,
                'clients' => $current_total_clients - $prev_total_clients,
            ]
        ];

        // ➤ Envoi email à tous les admins
        $admins = User::where('role', '!=', 3)->get();

        foreach ($admins as $admin) {
            Mail::send('emails.admin.monthlyStats', [
                'stats' => $stats
            ], function ($message) use ($admin) {
                $message->to($admin->email);
                $message->subject('📅 Rapport Mensuel - Statistiques Utilisateurs');
            });
        }
    }
}
