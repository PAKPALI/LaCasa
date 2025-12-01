<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District; // Assure-toi que le modèle existe

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        // Liste des quartiers de Lomé
        $districts = [
            'Abobokomé',
            'Ablogamé',
            'Abové',
            'Adakpamé',
            'Adawlato',
            'Adidogomé',
            'Adoboukomé',
            'Aflao-Gakli',
            'Agoè',
            'Agbadahonou',
            'Agbalépédogan',
            'Aguia Komé',
            'Akodesséwa-Kpota',
            'Akodesséwa-Kponou',
            'Akodésséwa',
            'Akossombo',
            'Amoutivé',
            'Anfamé',
            'Antonio-Nétimé',
            'Atiégou',
            'Avédji',
            'Avénou (Batome)',
            'Baguida',
            'Bassadji',
            'Bè',
            'Bè-Ahligo',
            'Bè-Apéyémé',
            'Bè-Hédjé',
            'Bè-Klikamé',
            'Bè-Kpota',
            'Béniglato',
            'Cassablanca',
            'Dékon',
            'Djidjolé',
            'Dogbéavou',
            'Doulassamé',
            'Doumasséssé',
            'Fréau-Jardin',
            'Gbényédi',
            'Gbonvié',
            'Gbonsimé',
            'Hanoukopé',
            'Hédzranawoé',
            'Kagnikopé',
            'Kélégougan',
            'Klikamé',
            'Kokétimé',
            'Kodjoviakopé',
            'Kotokou-Kondji',
            'Kpéhénou',
            'Kpogan',
            'Légbassito',
            'Lomé-2',
            'Lomé-Nava',
            'Noukafou',
            'Nyékonakpoé',
            'N’tifafakomé',
            'Octaviano-Nétimé',
            'Quartier Administratif',
            'Résidence du Bénin',
            'Saint-Joseph',
            'Sanguéra',
            'Ségbé',
            'Soviépé',
            'Souza Nétimé',
            'Tokoin-Aéroport',
            'Tokoin-Élavagnon',
            'Tokoin-Forever',
            'Tokoin-Gbadago',
            'Tokoin-Hôpital',
            'Tokoin-Lycée',
            'Tokoin-N’kafu',
            'Tokoin-Ouest',
            'Tokoin-Solidarité',
            'Tokoin-Tamé',
            'Tokoin-Wuiti',
            'Totsi',
            'Université de Lomé',
            'Wété',
            'Wétrivi-Kondji',
            'Zone Portuaire'
        ];


        foreach ($districts as $name) {
            // Vérifie si le quartier existe déjà pour town_id = 1
            District::firstOrCreate(
                ['name' => $name, 'town_id' => 1]
            );
        }
    }
}