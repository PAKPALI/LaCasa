<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // php artisan db:seed --class=SuperAdminSeeder
    public function run(): void
    {
        User::create([
            'name' => 'Didier PAKPALI',
            'email' => 'pakpalididier@gmail.com',
            'password' => Hash::make('Didier@95'), // mot de passe
            'user_type' => 1, // 1: personne, 2: agence
            'role' => 1,      // 1: super admin
            'country_id' => null, // si tu veux renseigner un pays, mets son ID
            'town_id' => null,
            'district_id' => null,
            'profile_image' => null, // si tu veux une image par défaut
        ]);
    }
}
