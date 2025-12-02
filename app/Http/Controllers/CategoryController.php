<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\SyncService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('country')->withCount('publications');

        // Filtrer si district_id passé (pour le formulaire)
        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request, SyncService $syncService){
        $error_messages = [
            "name.required"    => "Remplir le champ nom de la categorie !",
            "name.max"         => "Le nom du pays dépasse 255 caractères !",
            'country_id.required' => 'Sélectionner un pays',
            'country_id.exists'   => 'Le pays sélectionnée est invalide !',
        ];

        $validator = Validator::make($request->all(), [
            'name'       => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $category = Category::create($validator->validated());

         // Synchroniser les types depuis "Maison"
        // Copier tous les types + attributs depuis "Maison"
        $sourceCategory = Category::where('name', 'Maison')->first();
        if ($sourceCategory) {
            $syncService->syncPubTypesFromCategory($sourceCategory, $category);
        }

        return response()->json([
            "status"  => true,
            "message" => "Catégorie « ".$category->name." » ajoutée avec succès",
            "data"    => $category
        ]);
    }

    public function update(Request $request, Category $category){
        $error_messages = [
            "name.required"    => "Remplir le champ nom de la categorie !",
            "name.max"         => "Le nom du pays dépasse 255 caractères !",
            'country_id.required' => 'Sélectionner un pays',
            'country_id.exists'   => 'Le pays sélectionnée est invalide !',
        ];
        $validator = Validator::make($request->all(), [
            'name'       => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
        ], $error_messages);

        if ($validator->fails()) {
            return response()->json([
                "status"  => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $category->update($validator->validated());

        return response()->json([
            "status"  => true,
            "message" => "Catégorie « ".$category->name." » mise à jour avec succès",
            "data"    => $category
        ]);
    }

    public function destroy(Category $category) {
        if ($category->PubType()->exists()) {
            return response()->json([
                'status'  => false,
                'message' => 'Cette catégorie ne peut pas être supprimée car elle est liée à une ou plusieurs type de publication.'
            ]);
        }
        $category->delete();

        return response()->json([
            "status"  => true,
            "message" => "Catégorie supprimée avec succès"
        ]);
    }
}
