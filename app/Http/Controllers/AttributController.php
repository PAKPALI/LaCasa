<?php

namespace App\Http\Controllers;

use App\Models\Attribut;
use Illuminate\Http\Request;
use App\Services\SyncService;
use Illuminate\Support\Facades\Validator;

class AttributController extends Controller
{
    public function index(Request $request)
    {
        $query = Attribut::with('pubType.category.country');

        if ($request->has('pub_type_id')) {
            $query->where('pub_type_id', $request->pub_type_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request, SyncService $syncService)
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
        $syncService->replicateAttribute($attribute);

        return response()->json([
            'status'  => true,
            'message' => "Attribut « ".$attribute->name." » ajouté avec succès",
            'data'    => $attribute
        ]);
    }

    public function update(Request $request, $id, SyncService $syncService)
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
        $oldName = $attribute->name;
        $attribute->update($validator->validated());
        
        $syncService->updateAttributeSync($attribute, $oldName);

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

        if ($attribute->publications()->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'Cet attribut ne peut pas être supprimé car il est lié à une ou plusieurs publications.'
            ], 400);
        }

        $attribute->delete();

        return response()->json([
            'status'  => true,
            'message' => "Attribut supprimé ✅"
        ]);
    }
}
