<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
