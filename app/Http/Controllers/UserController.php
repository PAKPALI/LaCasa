<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with(['country', 'town', 'district'])
                ->orderBy('id', 'desc')
                ->get();

            return response()->json([
                "status" => true,
                "data" => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Erreur lors du chargement des utilisateurs : " . $e->getMessage()
            ], 500);
        }
    }

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

            $this->sendEmailMargin($user->name, $user->email, $request->password);

            $message = "Bienvenue " . $user->name . " sur LaCasa. Votre compte a été créé avec succès. Merci de nous faire confiance !";
            $user->phone1 ? $number = $user->phone1 : $number = $user->phone2;
            // $this->sendSms($number, $message);

            return response()->json([
                "status"  => true,
                "reload"  => true,
                "title"   => "ENREGISTREMENT RÉUSSI ✅",
                "message" => "L'utilisateur « " . $user->name . " » a été ajouté avec succès",
                "data"    => $user
            ]);

        } catch (\Exception $e) {
            log::info($e->getMessage());
            return response()->json([
                "status"  => false,
                "reload"  => false,
                "title"   => "ERREUR",
                "message" => "Une erreur est survenue lors de l'enregistrement : " . $e->getMessage()
            ]);
        }
    }

    public function sendEmailMargin($user_name, $email, $password)
    {
        Mail::send('emails.user.register', [
            'user_name' => $user_name,
            'email' => $email,
            'password' => $password,
        ], function($message) use ($email){
            $message->to($email);
            $message->subject(config('app.name') . ' - Bienvenue sur notre plateforme');
        });
    }


    public function sendSms($number, $message)
    {
        $smsService = new SmsService ();
        $response = $smsService->send($number, $message);
        log::info($response);
        return response()->json($response);

        // response example in format json
        // array (
        //     'status' => true,
        //     'message' => 'MESSAGE_SENT_SUCCESSFULLY',
        //     'data' => 
        //     array (
        //         'status' => 1,
        //         'response_token' => 'push_sms_afgrchw6re2bjnr',
        //     ),
        //     'status_code' => 200,
        // )
    }

    public function update(Request $request, $id)
    {
        Log::info($request->all());
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                "status"  => false,
                "reload"  => false,
                "title"   => "UTILISATEUR INTROUVABLE",
                "message" => "Aucun utilisateur trouvé avec cet identifiant."
            ]);
        }

        $error_messages = [
            "name.max"          => "Le nom ne doit pas dépasser 255 caractères !",
            "email.email"       => "L'adresse email n'est pas valide !",
            "email.unique"      => "Cette adresse email est déjà utilisée !",
            "password.min"      => "Le mot de passe doit contenir au moins 6 caractères !",
            "password.confirmed"=> "La confirmation du mot de passe ne correspond pas !",
            "role.in"           => "Le rôle sélectionné n'est pas valide !",
            "is_active.boolean" => "Le statut doit être actif ou inactif.",
            "profile_image.image" => "Le fichier doit être une image valide !",
            "profile_image.mimes" => "L'image doit être au format jpeg, png, jpg ou gif !",
            "profile_image.max"   => "L'image ne doit pas dépasser 2 Mo !",
        ];

        $validator = Validator::make($request->all(), [
            'name'          => ['nullable', 'string', 'max:255'],
            'email'         => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'password'      => ['nullable', 'string', 'min:6', 'confirmed'],
            'role'          => ['nullable', 'in:1,2,3'],
            'is_active'     => ['nullable', 'boolean'],
            'country_id'    => ['nullable', 'exists:countries,id'],
            'town_id'       => ['nullable', 'exists:towns,id'],
            'district_id'   => ['nullable', 'exists:districts,id'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "reload"  => false,
                "title"   => "MISE À JOUR ÉCHOUÉE",
                "message" => $validator->errors()->first()
            ]);
        }

        try {
            // 🔹 Mise à jour des champs modifiés uniquement
            $data = $request->only([
                'name', 'email', 'role', 'is_active',
                'country_id', 'town_id', 'district_id'
            ]);

            // 🔹 Mot de passe (si fourni)
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // 🔹 Upload de la nouvelle image
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('LaCasa/pub'), $imageName);

                // Suppression ancienne image si existante
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    @unlink(public_path($user->profile_image));
                }

                $data['profile_image'] = 'LaCasa/pub/' . $imageName;
            }

            $user->update($data);

            return response()->json([
                "status"  => true,
                "reload"  => true,
                "title"   => "MISE À JOUR RÉUSSIE ✅",
                "message" => "Les informations de l'utilisateur « {$user->name} » ont été mises à jour avec succès.",
                "data"    => $user->fresh(['country', 'town', 'district']),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status"  => false,
                "reload"  => false,
                "title"   => "ERREUR",
                "message" => "Une erreur est survenue lors de la mise à jour : " . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['status'=>true,'message'=>'Utilisateur supprimé ✅']);
    }
}
