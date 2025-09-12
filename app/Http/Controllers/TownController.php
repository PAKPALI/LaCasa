<?php

namespace App\Http\Controllers;

use App\Models\Town;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TownController extends Controller
{
    public function index()
    {
        // Charger aussi le pays lié pour l'afficher dans la table
        return response()->json(Town::with('country')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
        ], [
            'name.required' => 'Le nom de la ville est obligatoire',
            'country_id.required' => 'Choisissez un pays',
            'country_id.exists'   => 'Le pays sélectionné n’existe pas'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'title'   => 'ENREGISTREMENT ÉCHOUÉ',
                'message' => $validator->errors()->first(),
            ]);
        }

        $town = Town::create($validator->validated());

        return response()->json([
            'status'  => true,
            'title'   => 'ENREGISTREMENT RÉUSSI ✅',
            'message' => "La ville « {$town->name} » a été ajoutée avec succès",
            'data'    => $town->load('country')
        ]);
    }

    public function show(Town $town)
    {
        return response()->json($town->load('country'));
    }

    public function update(Request $request, Town $town)
    {
        $validator = Validator::make($request->all(), [
            'name'       => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'title'   => 'MISE À JOUR ÉCHOUÉE',
                'message' => $validator->errors()->first(),
            ]);
        }

        $town->update($validator->validated());

        return response()->json([
            'status'  => true,
            'title'   => 'MISE À JOUR RÉUSSIE ✅',
            'message' => "La ville « {$town->name} » a été mise à jour",
            'data'    => $town->load('country')
        ]);
    }

    public function destroy(Town $town)
    {
        // Ici on ne supprime pas si le pays existe toujours
        if ($town->country) {
            return response()->json([
                'status'  => false,
                'title'   => 'SUPPRESSION REFUSÉE',
                'message' => 'Vous ne pouvez pas supprimer cette ville sans supprimer son pays.'
            ]);
        }

        $town->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Ville supprimée avec succès'
        ]);
    }
}
