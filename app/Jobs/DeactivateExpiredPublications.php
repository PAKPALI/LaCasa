<?php

namespace App\Jobs;

use App\Models\Publication;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

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
                Log::info("Publication {$publication->price} désactivée !");

                if ($publication->user && $publication->user->email) {
                    Log::info("Email envoyé !");
                    // Mail::to($publication->user->email)
                    //     ->send(new PublicationDeactivatedMail($publication));
                }
            }
        }
        Log::info('Fin du job à : ' . now());
    }
}
