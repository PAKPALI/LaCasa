<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $query = District::with(['town.country']);

        // Filtrer si town_id passé (pour le formulaire)
        if ($request->has('town_id')) {
            $query->where('town_id', $request->town_id);
        }

        // Si aucun filtre, on renvoie tout pour la liste globale
        return response()->json($query->get());
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => ['required', 'string', 'max:255'],
            'town_id' => ['required', 'exists:towns,id'],
        ], [
            'name.required'    => 'Le nom du quartier est obligatoire',
            'town_id.required' => 'Choisissez une ville',
            'town_id.exists'   => 'La ville sélectionnée n’existe pas'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'title'   => 'ENREGISTREMENT ÉCHOUÉ',
                'message' => $validator->errors()->first(),
            ]);
        }

        $district = District::create($validator->validated());

        return response()->json([
            'status'  => true,
            'title'   => 'ENREGISTREMENT RÉUSSI ✅',
            'message' => "Le quartier « {$district->name} » a été ajouté avec succès",
            'data'    => $district->load('town.country')
        ]);
    }

    public function show(District $district)
    {
        return response()->json($district->load('town.country'));
    }

    public function update(Request $request, District $district)
    {
        $validator = Validator::make($request->all(), [
            'name'    => ['required', 'string', 'max:255'],
            'town_id' => ['required', 'exists:towns,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'title'   => 'MISE À JOUR ÉCHOUÉE',
                'message' => $validator->errors()->first(),
            ]);
        }

        $district->update($validator->validated());

        return response()->json([
            'status'  => true,
            'title'   => 'MISE À JOUR RÉUSSIE ✅',
            'message' => "Le quartier « {$district->name} » a été mis à jour",
            'data'    => $district->load('town.country')
        ]);
    }

    public function destroy(District $district)
    {
        $district->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Quartier supprimé avec succès'
        ]);
    }
}
