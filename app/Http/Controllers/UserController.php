<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $error_messages = [
            "name.required"                  => "Le nom de l'utilisateur est requis !",
            "name.max"                       => "Le nom ne doit pas dépasser 255 caractères !",
            "email.required"                 => "L'adresse email est requise !",
            "email.email"                    => "L'adresse email n'est pas valide !",
            "email.unique"                   => "Cette adresse email est déjà utilisée !",
            "password.required"              => "Le mot de passe est requis !",
            "password.min"                   => "Le mot de passe doit contenir au moins 6 caractères !",
            "password.confirmed"             => "La confirmation du mot de passe ne correspond pas !",
            "user_type.required"             => "Veuillez choisir votre type (Personne ou Agence) !",
            "profile_image.image"            => "Le fichier doit être une image valide !",
            "profile_image.mimes"            => "L'image doit être au format jpeg, png, jpg ou gif !",
            "profile_image.max"              => "L'image ne doit pas dépasser 2 Mo !",
        ];

        $validator = Validator::make($request->all(), [
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'password'       => ['required', 'string', 'min:6', 'confirmed'],
            'user_type'      => ['required', 'in:1,2'],
            'phone1'         => ['nullable', 'string', 'max:20'],
            'phone2'         => ['nullable', 'string', 'max:20'],
            'country_id'     => ['nullable', 'exists:countries,id'],
            'town_id'        => ['nullable', 'exists:towns,id'],
            'district_id'    => ['nullable', 'exists:districts,id'],
            'profile_image'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "reload"  => false,
                "title"   => "ENREGISTREMENT ÉCHOUÉ",
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            // Création de l'utilisateur
            $user = User::create([
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'user_type'   => $request->user_type,
                'role'        => 3, // client par défaut
                'phone1'      => $request->phone1,
                'phone2'      => $request->phone2,
                'country_id'  => $request->country_id,
                'town_id'     => $request->town_id,
                'district_id' => $request->district_id,
            ]);

            // Gestion de l'image de profil (si upload)
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('LaCasa/pub'), $imageName);

                // Si tu stockes directement dans la colonne profile_image
                $user->profile_image = 'LaCasa/pub/' . $imageName;
                $user->save();

                // Si tu as une relation images (optionnel)
                // $user->images()->create(['path' => 'LaCasa/pub/' . $imageName]);
            }

            return response()->json([
                "status"  => true,
                "reload"  => true,
                "title"   => "ENREGISTREMENT RÉUSSI ✅",
                "message" => "L'utilisateur « " . $user->name . " » a été ajouté avec succès",
                "data"    => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status"  => false,
                "reload"  => false,
                "title"   => "ERREUR",
                "message" => "Une erreur est survenue lors de l'enregistrement : " . $e->getMessage()
            ]);
        }
    }
}
