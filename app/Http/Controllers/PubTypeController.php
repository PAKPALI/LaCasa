<?php

namespace App\Http\Controllers;

use App\Models\PubType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\SyncService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PubTypeController extends Controller
{
    // Liste tous les types
    public function index(Request $request)
    {
        $query = PubType::with('category.country');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return response()->json($query->get());
    }

    // Ajouter un type
    public function store(Request $request, SyncService $syncService){
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
        // Répliquer vers toutes les autres catégories
        $category = $pubType->category;

        // 🔁 Définir le type source selon la catégorie
        if ($category->name === 'Terrain') {
            $sourceType = PubType::where('category_id', $category->id)
                ->where('name', '1/4 lot')
                ->first();
        } else {
            $sourceCategory = Category::where('name', 'Maison')->first();
            $sourceType = $sourceCategory?->PubType()
                ->where('name', 'Piece')
                ->first();
        }

        // Copier les attributs depuis le type source
        if ($sourceType) {
            $syncService->replicateAttributes($sourceType, $pubType);
        }

        // 2️⃣ Répliquer le PubType principal avec ses attributs vers toutes les autres catégories
        $syncService->replicatePubType($pubType);

        return response()->json([
            "status"  => true,
            "message" => "Type de pub « ".$pubType->name." » ajouté avec succès",
            "data"    => $pubType
        ]);
    }

    // Mettre à jour un type
    public function update(Request $request, $id, SyncService $syncService)
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
        $oldName = $pubType->name;
        $pubType->update($validator->validated());
        
        $syncService->updatePubTypeSync($pubType, $oldName);

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

        if ($pubType->Attribut()->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'Cet type de pub ne peut pas être supprimé car il est lié à une ou plusieurs attributs.'
            ], 400);
        }

        $pubType->delete();

        return response()->json([
            "status" => true,
            "message" => "Type de pub supprimé ✅"
        ]);
    }
}
