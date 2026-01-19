<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Publication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeactivateExpiredPublications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $publications = Publication::where('is_active', true)->get();

        foreach ($publications as $publication) {
            if ($publication->shouldBeDeactivated()) {
                $publication->update(['is_active' => false, 'deactivated_at' => now()] );
                // verifier si l'utilisateur existe avant d'envoyer l'email
                if ($publication->user && $publication->user->email) {
                    // verifier le role de l'utilisateur pour envoyer l'email aux admins si c'est un admin
                    if($publication->user->role == 1 || $publication->user->role == 2){
                        $admins = User::where('role', '!=', 3)->get();
                        foreach ($admins as $admin) {
                            $this->sendEmailMargin($admin->name, $admin->email, $publication->code);
                        }
                    }else{
                        $this->sendEmailMargin($publication->user->name, $publication->user->email, $publication->code);
                    }
                }
            }
        }
        Log::info($publications->count().' pub trouvées ; fin du job de désactivation à : ' . now());
    }

    public function sendEmailMargin($user_name, $email, $code)
    {
        try {
            // Envoyez l'e-mail avec le code généré
            Mail::send('emails.publication.deactivated', ['user_name' => $user_name, 'code' => $code], function($message) use ($email){
                $message->to($email);
                $message->subject(config('app.name') . ' - Publication désactivée');
            });
            log::info("Envoi de l'email de désactivation à : " . $email);
        } catch (\Throwable $e) {
            Log::error('Erreur mail prod', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
        }
    }
}
