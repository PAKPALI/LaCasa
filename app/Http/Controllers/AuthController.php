<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        $error_messages = [
            "email.required"    => "L'adresse email est requise.",
            "email.email"       => "L'adresse email est invalide.",
            "password.required" => "Le mot de passe est requis.",
        ];

        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => $validator->errors()->first(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return response()->json([
                "status"  => false,
                "message" => "Email ou mot de passe incorrect.",
            ], 401);
        }

        // 🔐 Auth::attempt a connecté l'utilisateur
        $user = Auth::user();
        Log::info(Auth::check());

        // Réponse JSON pour Vue
        return response()->json([
            "status"  => true,
            "message" => "Connexion réussie ✅",
            "user"    => [
                "id"         => $user->id,
                "name"       => $user->name,
                "email"      => $user->email,
                "role"       => $user->role,
                "user_type"  => $user->user_type,
                "is_active"  => $user->is_active,
            ],
        ]);
    }

     // 🔹 Récupère les infos de l’utilisateur connecté
    public function me()
    {
        return response()->json(Auth::user()->load(['country', 'town', 'district']));
    }

    // 🔹 Met à jour les infos générales (sauf email et mot de passe)
    public function update(Request $request)
    {
        $user = Auth::user();

        $error_messages = [
            "name.required"                  => "Le nom de l'utilisateur est requis !",
            "name.max"                       => "Le nom ne doit pas dépasser 255 caractères !",
            "profile_image.image"            => "Le fichier doit être une image valide !",
            "profile_image.mimes"            => "L'image doit être au format jpeg, png, jpg ou gif !",
            "profile_image.max"              => "L'image ne doit pas dépasser 2 Mo !",
        ];

        $validator = Validator::make($request->all(), [
            'name'           => ['required', 'string', 'max:255'],
            'phone1'         => ['nullable', 'string', 'max:20'],
            'phone2'         => ['nullable', 'string', 'max:20'],
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

        // 📸 Upload d’image si présente
        // if ($request->hasFile('profile_image')) {
        //     $path = $request->file('profile_image')->store('profiles', 'public');
        //     $user->profile_image = $path;
        // }
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('LaCasa/pub'), $imageName);

            // Si tu stockes directement dans la colonne profile_image
            $user->profile_image = 'LaCasa/pub/' . $imageName;
            $user->save();
        }

        $user->fill($request->except(['email', 'password', 'profile_image', 'role']));
        $user->save();

        return response()->json(['status' => true, 'message' => 'Profil mis à jour', 'user' => $user]);
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password_confirmation' => ['required'],
        ], [
            'email.required' => "L'adresse email est requise !",
            'email.email' => "L'adresse email n'est pas valide !",
            'email.unique' => "Cette adresse email est déjà utilisée !",
            'password_confirmation.required' => "Veuillez entrer votre mot de passe pour confirmer le changement d'email.",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => $validator->errors()->first(),
            ], 422);
        }

        // 🔐 Vérification du mot de passe
        if (!Hash::check($request->password_confirmation, $user->password)) {
            return response()->json([
                "status"  => false,
                "message" => "Le mot de passe fourni est incorrect.",
            ], 403);
        }

        $user->email = $request->email;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Email mis à jour avec succès.',
            'user' => $user,
        ]);
    }

    // 🔹 Met à jour le mot de passe
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => "Veuillez saisir votre mot de passe actuel.",
            'new_password.required' => "Veuillez saisir un nouveau mot de passe.",
            'new_password.min' => "Le nouveau mot de passe doit contenir au moins 6 caractères.",
            'new_password.confirmed' => "La confirmation du nouveau mot de passe ne correspond pas.",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Vérification du mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => "Le mot de passe actuel saisi est incorrect."
            ], 403);
        }

        // Mise à jour du mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => "Votre mot de passe a été mis à jour avec succès ✅"
        ]);
    }

    /**
     * Déconnexion utilisateur
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            "status"  => true,
            "message" => "Déconnexion réussie.",
        ]);
    }
}
