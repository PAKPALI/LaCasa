<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribut;
use Illuminate\Support\Facades\Validator;

class AttributController extends Controller
{
    public function index()
    {
        $attributes = Attribut::with('pubType.category.country')->orderBy('id', 'desc')->get();
        return response()->json($attributes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => ['required', 'string', 'max:255'],
            'pub_type_id' => ['required', 'exists:pub_types,id'],
        ], [
            'name.required'        => 'Remplir le nom de l’attribut !',
            'name.max'             => 'Le nom dépasse 255 caractères !',
            'pub_type_id.required' => 'Sélectionner un type de pub !',
            'pub_type_id.exists'   => 'Type de pub invalide !',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $attribute = Attribut::create($validator->validated());

        return response()->json([
            'status'  => true,
            'message' => "Attribut « ".$attribute->name." » ajouté avec succès",
            'data'    => $attribute
        ]);
    }

    public function update(Request $request, $id)
    {
        $attribute = Attribut::find($id);
        if (!$attribute) {
            return response()->json([
                'status'  => false,
                'message' => 'Attribut introuvable',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name'        => ['required', 'string', 'max:255'],
            'pub_type_id' => ['required', 'exists:pub_types,id'],
        ], [
            'name.required'        => 'Remplir le nom de l’attribut !',
            'name.max'             => 'Le nom dépasse 255 caractères !',
            'pub_type_id.required' => 'Sélectionner un type de pub !',
            'pub_type_id.exists'   => 'Type de pub invalide !',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $attribute->update($validator->validated());

        return response()->json([
            'status'  => true,
            'message' => "Attribut « ".$attribute->name." » mis à jour avec succès",
            'data'    => $attribute
        ]);
    }

    public function destroy($id)
    {
        $attribute = Attribut::find($id);
        if (!$attribute) {
            return response()->json([
                'status'  => false,
                'message' => 'Attribut introuvable',
            ]);
        }

        $attribute->delete();

        return response()->json([
            'status'  => true,
            'message' => "Attribut supprimé ✅"
        ]);
    }
}
