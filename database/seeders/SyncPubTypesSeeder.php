<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\PubType;

class SyncPubTypesSeeder extends Seeder
{
    public function run(): void
    {
        // Choisir la catégorie "source" dont les types seront copiés
        $sourceCategory = Category::where('name', 'Maison')->first();

        if (!$sourceCategory) {
            echo 'Catégorie Maison introuvable !';
            return;
        }

        // Récupérer tous les types liés à la catégorie source
        $sourceTypes = $sourceCategory->pubTypes()->pluck('name')->toArray();

        // Parcourir toutes les catégories
        $categories = Category::all();

        foreach ($categories as $category) {
            foreach ($sourceTypes as $typeName) {
                // Vérifier si le type existe déjà pour cette catégorie
                $exists = PubType::where('category_id', $category->id)
                                 ->where('name', $typeName)
                                 ->exists();

                if (!$exists) {
                    // Créer le type pour la catégorie actuelle
                    PubType::create([
                        'name' => $typeName,
                        'category_id' => $category->id,
                    ]);
                    echo "Type '$typeName' créé pour la catégorie '{$category->name}";
                }
            }
        }
    }
}
