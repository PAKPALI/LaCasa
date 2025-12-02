<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class CheckCertificationExpiry extends Command
{
    protected $signature = 'certification:check';
    protected $description = 'Vérifie les certifications expirées (validité 1 an)';

    public function handle()
    {
        $now = Carbon::now();

        // Sélectionner uniquement les utilisateurs certifiés
        $users = User::where('certify_payment_status', true)
            ->whereNotNull('certify_payment_date')
            ->get();

        foreach ($users as $user) {
            $expiryDate = Carbon::parse($user->certify_payment_date)->addYear();

            if ($expiryDate->isPast()) {
                $user->update([
                    'certify_payment_status' => false,
                    'is_verified' => false,
                ]);

                $this->info("Certification expirée pour l'utilisateur ID {$user->id}");
            }
        }

        return Command::SUCCESS;
    }
}