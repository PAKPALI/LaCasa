<?php

namespace App\Jobs;

use App\Models\Publication;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteInactivePublications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function handle(): void
    {
        $publications = Publication::where('is_active', false)->get();

        foreach ($publications as $publication) {
            $deactivationDate = $publication->deactivated_at ?? $publication->updated_at;

            if (\Carbon\Carbon::now()->greaterThanOrEqualTo($deactivationDate->copy()->addDays(30))) {

                // Supprimer les images
                foreach ($publication->images as $img) {
                    $imagePath = public_path($img->path);
                    if (file_exists($imagePath)) unlink($imagePath);
                    $img->delete();
                }

                $publication->delete();

                Log::info("Publication {$publication->code} supprimée après 30 jours d'inactivité.");
            }
        }
    }
}

