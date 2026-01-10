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

class DeleteInactivePublications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $publications = Publication::where('is_active', false)->get();

        foreach ($publications as $publication) {
            if ($publication->shouldBeDeleted()) {

                // Supprimer les images
                foreach ($publication->images as $img) {
                    $imagePath = public_path($img->path);
                    if (file_exists($imagePath)) unlink($imagePath);
                    $img->delete();
                }

                if ($publication->user && $publication->user->email) {
                    $this->sendEmailMargin($publication->user->name, $publication->user->email, $publication->code);
                }

                $publication->delete();

                // Log::info("Publication {$publication->code} supprimée après 30 jours d'inactivité.");
            }
        }
        Log::info($publications->count().' pub trouvées ; Fin du job  de suppression de pub inactive à : ' . now());
    }
    public function sendEmailMargin($user_name, $email, $code)
    {
        try {
            // Envoyez l'e-mail avec le code généré
            Mail::send('emails.publication.deleted', ['user_name' => $user_name, 'code' => $code,], function($message) use ($email){
                $message->to($email);
                $message->subject(config('app.name') . ' - Publication supprimée');
            });
            log::info("Envoi de l'email de suppression de pub inactive à : " . $email);
        } catch (\Throwable $e) {
            Log::error('Erreur mail prod', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
        }
    }
}

