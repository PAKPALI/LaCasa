<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PubType;
use Illuminate\Support\Facades\Validator;

class PubTypeController extends Controller
{
    // Liste tous les types
    public function index()
    {
        // $pubTypes = PubType::with('category')->orderBy('id','desc')->get();
        $pubTypes = PubType::with('category.country')->get();
        return response()->json($pubTypes);
    }

    // Ajouter un type
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => ['required','string','max:255'],
            'category_id' => ['required','exists:categories,id'],
        ], [
            'name.required'        => 'Remplir le champ nom !',
            'name.max'             => 'Le nom dépasse 255 caractères !',
            'category_id.required' => 'Sélectionner une catégorie !',
            'category_id.exists'   => 'La catégorie sélectionnée est invalide !',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first()
            ]);
        }

        $pubType = PubType::create($validator->validated());

        return response()->json([
            "status"  => true,
            "message" => "Type de pub « ".$pubType->name." » ajouté avec succès",
            "data"    => $pubType
        ]);
    }

    // Mettre à jour un type
    public function update(Request $request, $id)
    {
        $pubType = PubType::find($id);
        if (!$pubType) {
            return response()->json([
                "status" => false,
                "message" => "Type de pub introuvable"
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name'        => ['required','string','max:255'],
            'category_id' => ['required','exists:categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->first()
            ]);
        }

        $pubType->update($validator->validated());

        return response()->json([
            "status" => true,
            "message" => "Type de pub « ".$pubType->name." » mis à jour avec succès",
            "data" => $pubType
        ]);
    }

    // Supprimer un type
    public function destroy($id)
    {
        $pubType = PubType::find($id);
        if (!$pubType) {
            return response()->json([
                "status" => false,
                "message" => "Type de pub introuvable"
            ]);
        }

        $pubType->delete();

        return response()->json([
            "status" => true,
            "message" => "Type de pub supprimé ✅"
        ]);
    }
}
