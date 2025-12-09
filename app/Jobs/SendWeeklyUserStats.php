<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendWeeklyUserStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            // 🔵 Dates de la semaine actuelle (qui se termine)
            $endOfWeek   = Carbon::now()->endOfWeek();     // Dimanche
            $startOfWeek = Carbon::now()->startOfWeek();   // Lundi

            // 🔵 Dates de la semaine précédente
            $prevStart = Carbon::now()->subWeek()->startOfWeek();
            $prevEnd   = Carbon::now()->subWeek()->endOfWeek();

            // =============================
            // 📊 STATS SEMAINE ACTUELLE
            // =============================
            $current_total_users = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            $current_total_agency = User::where('user_type', 2)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();
            $current_total_clients = User::where('user_type', 1)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            // =============================
            // 📊 STATS SEMAINE PRÉCÉDENTE
            // =============================
            $prev_total_users = User::whereBetween('created_at', [$prevStart, $prevEnd])->count();
            $prev_total_agency = User::where('user_type', 2)
                ->whereBetween('created_at', [$prevStart, $prevEnd])
                ->count();
            $prev_total_clients = User::where('user_type', 1)
                ->whereBetween('created_at', [$prevStart, $prevEnd])
                ->count();

            // =============================
            // 📈 CALCUL DES ÉVOLUTIONS
            // =============================
            $stats = [
                'period' => [
                    'current' => $startOfWeek->format('d/m/Y').' - '.$endOfWeek->format('d/m/Y'),
                    'previous' => $prevStart->format('d/m/Y').' - '.$prevEnd->format('d/m/Y'),
                ],
                'current_week' => [
                    'total_users' => $current_total_users,
                    'total_agency' => $current_total_agency,
                    'total_clients' => $current_total_clients,
                ],
                'previous_week' => [
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

            // 📧 ENVOI AUX ADMINS
            $admins = User::where('role', '!=', 3)->get();

            foreach ($admins as $admin) {
                Mail::send('emails.admin.weeklyStats', [
                    'stats' => $stats
                ], function($message) use ($admin) {
                    $message->to($admin->email);
                    $message->subject('📊 Rapport Hebdomadaire - Statistiques Utilisateurs');
                });
            }

        } catch (\Exception $e) {
            Log::error("Erreur Weekly Stats: ".$e->getMessage());
        }
    }
}