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

class WarningDeleteInactivePublications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $publications = Publication::where('is_active', false)->get();

        foreach ($publications as $publication) {
            // 🔹 1. Envoi d’un avertissement 5 jours avant suppression
            if ($publication->shouldSendDeletionWarning()) {
                $this->sendEmailMargin($publication->user->name, $publication->user->email, $publication->code);
                // Log::success("Alert de suppression envoyer pour la publication {$publication->code}.");
            }
        }
        Log::info($publications->count().' pub trouvées ; Fin du job de prevention de suppression de pub inactive à : ' . now());
    }
    public function sendEmailMargin($user_name, $email, $code)
    {
        // Envoyez l'e-mail avec le code généré
        Mail::send('emails.publication.warningDeleted', ['user_name' => $user_name, 'code' => $code], function($message) use ($email){
            $message->to($email);
            $message->subject(config('app.name') . ' - Publication supprimée');
        });
    }
}

