<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckCertificationExpiry extends Command
{
    protected $signature = 'certification:check';
    protected $description = 'Vérifie les certifications expirées (validité 1 an)';

    public function handle()
    {
        $now = Carbon::now();

        // Sélectionner uniquement les utilisateurs certifiés
        $users = User::where('certify_payment_status', true)->whereNotNull('certify_payment_date')->get();

        if($users->count()>0){
            foreach ($users as $user) {
                $expiryDate = Carbon::parse($user->certify_payment_date)->addYear();

                if ($expiryDate->isPast()) {
                    $user->update([
                        'certify_payment_status' => false,
                        'is_verified' => false,
                    ]);
                    Log::info("Certification expirée pour l'utilisateur ID {$user->id}");
                }
            }
        }else{
            Log::info("Certification expire non trouve");
            echo "Certification non trouver\n";
        }

        return Command::SUCCESS;
    }
}