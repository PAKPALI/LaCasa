<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function index()
    {
        return response()->json(Country::all());
    }

    public function store(Request $request)
    {
        $error_messages = [
            "name.required"    => "Remplir le champ nom du pays !",
            "name.max"         => "Le nom du pays dépasse 255 caractères !",
            "acronym.required" => "Remplir le champ acronyme !",
            // "acronym.max"      => "L'acronyme ne doit pas dépasser 5 caractères !",
            "code.required"    => "Remplir le champ code !",
            "code.numeric"     => "Le code doit être un nombre !",
            // "code.max"      => "L'acronyme ne doit pas dépasser 10 caractères !",
        ];

        $validator = Validator::make($request->all(), [
            'name'    => ['required', 'string', 'max:255'],
            'acronym' => ['required', 'string'],
            'code'    => ['required', 'numeric'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title"  => "ENREGISTREMENT ÉCHOUÉ",
                "message"    => $validator->errors()->first()
            ]);
        } else {
            $country = Country::create([
                'name'    => $request->name,
                'acronym' => $request->acronym,
                'code'    => $request->code,
            ]);

            return response()->json([
                "status" => true,
                "reload" => true,
                "title"  => "ENREGISTREMENT RÉUSSI ✅",
                "message"    => "Le pays « ".$country->name." » a été ajouté avec succès",
                "data"   => $country
            ]);
        }
    }

    public function show(Country $country)
    {
        return response()->json($country);
    }

    public function update(Request $request, Country $country)
    {
        $error_messages = [
            "name.required"    => "Remplir le champ nom du pays !",
            "name.max"         => "Le nom du pays dépasse 255 caractères !",
            "acronym.required" => "Remplir le champ acronyme !",
            "acronym.max"      => "L'acronyme ne doit pas dépasser 5 caractères !",
            "code.required"    => "Remplir le champ code !",
            "code.numeric"     => "Le code doit être un nombre !",
            "code.max"      => "L'acronyme ne doit pas dépasser 10 caractères !",
        ];

        $validator = Validator::make($request->all(), [
            'name'    => ['required', 'string', 'max:255'],
            'acronym' => ['required', 'string'],
            'code'    => ['required', 'numeric'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "reload" => false,
                "title"  => "MISE À JOUR ÉCHOUÉE",
                "message"    => $validator->errors()->first()
            ]);
        }

        // Mise à jour
        $country->update([
            'name'    => $request->name,
            'acronym' => $request->acronym,
            'code'    => $request->code,
        ]);

        return response()->json([
            "status" => true,
            "reload" => true,
            "title"  => "MISE À JOUR RÉUSSIE ✅",
            "message"    => "Le pays « ".$country->name." » a été modifié avec succès",
            "data"   => $country
        ]);
    }

    public function destroy(Country $country)
    {
        if ($country->towns()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Impossible de supprimer ce pays car des villes y sont liées.'
            ]);
        }

        $country->delete();

        return response()->json([
            'status' => true,
            'message' => 'Pays supprimé avec succès'
        ]);
    }

}
