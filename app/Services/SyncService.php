<?php

namespace App\Services;

use App\Models\PubType;
use App\Models\Attribut;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class SyncService
{
    // Copier un PubType vers toutes les autres catégories
    public function replicatePubType(PubType $pubType): void
    {
        $categories = Category::where('id', '!=', $pubType->category_id)->get();

        foreach ($categories as $category) {
            $exists = PubType::where('category_id', $category->id)
                             ->where('name', $pubType->name)
                             ->exists();

            if (!$exists) {
                $newType = PubType::create([
                    'name' => $pubType->name,
                    'category_id' => $category->id,
                ]);

                // Copier tous les attributs déjà présents du type principal
                foreach ($pubType->Attribut ?? [] as $attribute) {
                    Attribut::create([
                        'name' => $attribute->name,
                        'pub_type_id' => $newType->id,
                    ]);
                }
            }
        }
    }

    // Copier les attributs d'un type source vers un type cible
    public function replicateAttributes(PubType $source, PubType $target): void
    {
        foreach ($source->Attribut ?? [] as $attribute) {
            $exists = Attribut::where('pub_type_id', $target->id)
                              ->where('name', $attribute->name)
                              ->exists();

            if (!$exists) {
                Attribut::create([
                    'name' => $attribute->name,
                    'pub_type_id' => $target->id,
                ]);
            }
        }
    }

    // Copier un attribut vers tous les types de la même catégorie
    public function replicateAttribute(Attribut $attribute): void
    {
        $pubTypes = PubType::where('id', '!=', $attribute->pub_type_id)
                        //    ->where('category_id', $attribute->pubType->category_id)
                           ->get();

        foreach ($pubTypes as $pubType) {
            $exists = Attribut::where('pub_type_id', $pubType->id)
                              ->where('name', $attribute->name)
                              ->exists();

            if (!$exists) {
                Attribut::create([
                    'name' => $attribute->name,
                    'pub_type_id' => $pubType->id,
                ]);
            }
        }
    }

    // Copier tous les PubTypes d'une catégorie source vers une catégorie cible
    public function syncPubTypesFromCategory(Category $source, Category $target): void
    {
        foreach ($source->PubType ?? [] as $sourceType) {
            $exists = PubType::where('category_id', $target->id)
                            ->where('name', $sourceType->name)
                            ->exists();

            if (!$exists) {
                $newType = PubType::create([
                    'name' => $sourceType->name,
                    'category_id' => $target->id,
                ]);

                // Répliquer les attributs du type source vers le nouveau type
                foreach ($sourceType->attributes ?? [] as $attribute) {
                    Attribut::create([
                        'name' => $attribute->name,
                        'pub_type_id' => $newType->id,
                    ]);
                }
            }
        }
    }

    public function updatePubTypeSync(PubType $pubType, string $oldName): void
    {
        PubType::where('name', $oldName)
            ->where('id', '!=', $pubType->id)
            ->update([
                'name' => $pubType->name
            ]);
    }

    public function updateAttributeSync(Attribut $attribute, string $oldName): void
    {
        Attribut::where('name', $oldName)
            ->where('id', '!=', $attribute->id)
            ->update([
                'name' => $attribute->name
            ]);
    }

}