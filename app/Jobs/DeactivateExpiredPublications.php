<?php

namespace App\Jobs;

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
        Log::info('Début du job de désactivation à : ' . now());

        $publications = Publication::where('is_active', true)->get();
        Log::info('count: ' . $publications->count());

        foreach ($publications as $publication) {
            if ($publication->shouldBeDeactivated()) {
                $publication->update(['is_active' => false]);
                if ($publication->user && $publication->user->email) {
                    $this->sendEmailMargin($publication->user->name, $publication->user->email, $publication->code);
                }
            }
        }
        Log::info('Fin du job à : ' . now());
    }

    public function sendEmailMargin($user_name, $email, $code)
    {
        log::info("Envoi de l'email de désactivation à : " . $email);
        // Envoyez l'e-mail avec le code généré
        Mail::send('emails.publication.deactivated', ['user_name' => $user_name, 'code' => $code], function($message) use ($email){
            $message->to($email);
            $message->subject(config('app.name') . ' - Publication désactivée');

            // ✅ Ajout manuel des en-têtes de priorité
            // $headers = $message->getHeaders();
            // $headers->addTextHeader('X-Priority', '1');
            // $headers->addTextHeader('X-MSMail-Priority', 'High');
            // $headers->addTextHeader('Importance', 'High');
        });
    }
}
