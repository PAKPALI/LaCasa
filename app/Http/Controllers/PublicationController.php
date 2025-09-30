<?php

// app/Http/Controllers/PublicationController.php
namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Publication::with([
            'pubType:id,name',
            'category:id,name',
            'district:id,name',
            'town:id,name',
            'country:id,name',
            'images',
            'attributes:id,name'
        ]);

        // 🔹 FILTRAGE DYNAMIQUE
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        if ($request->filled('town_id')) {
            $query->where('town_id', $request->town_id);
        }
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('pub_type_id')) {
            $query->where('pub_type_id', $request->pub_type_id);
        }
        if ($request->filled('attribute_ids')) {
            $attributeIds = $request->attribute_ids;

            $query->whereHas('attributes', function ($q) use ($attributeIds) {
                $q->whereIn('attributes.id', $attributeIds);
            }, '=', count($attributeIds));
        }
        log::info('🔍 FILTRES REÇUS', $request->all());
        if ($request->filled('price1') && $request->filled('price2')) {
            $query->whereBetween('price', [$request->price1, $request->price2]);
        } elseif ($request->filled('price1')) {
            $query->where('price', $request->price1);
        } elseif ($request->filled('price2')) {
            $query->where('price', $request->price2);
        }

        // 🔹 LIMITATION AU CHARGEMENT INITIAL
        if ($request->filled('limit')) {
            $query->latest()->take($request->limit);
        }

        $publications = $query->get();

        $formatted = $publications->map(function ($pub) {
            return [
                'id' => $pub->id,
                'title' => $pub->pubType->name ?? 'Type inconnu',
                'price' => $pub->price,
                'bathroom' => $pub->bathroom,
                'surface' => $pub->surface,
                'advance' => $pub->advance,
                'deposit' => $pub->deposit,
                'description' => $pub->description,
                'visit' => $pub->visit,
                'offer_type' => $pub->offer_type,
                'is_active' => $pub->is_active,
                'category_name' => $pub->category->name ?? 'Catégorie inconnue',
                'district_name' => $pub->district->name ?? 'Non défini',
                'town_name' => $pub->town->name ?? 'Non défini',
                'country_name' => $pub->country->name ?? 'Non défini',
                'images' => $pub->images->map(fn($img) => '/'.$img->path),
                'phone1' => ($pub->phone1 && $pub->phone1 !== 'null') ? $pub->phone1 : null,
                'phone2' => ($pub->phone2 && $pub->phone2 !== 'null') ? $pub->phone2 : null,
                'attributes' => $pub->attributes->map(fn($attr) => ['id' => $attr->id, 'name' => $attr->name])
            ];
        });

        return response()->json($formatted);
    }


    public function show($id)
    {
        $publication = Publication::with([
            'pubType:id,name',
            'category:id,name',
            'district:id,name',
            'town:id,name',
            'country:id,name',
            'images',
            'attributes:id,name'
        ])->findOrFail($id);

        return response()->json($publication);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'country_id'  => ['required','exists:countries,id'],
                'town_id'     => ['required','exists:towns,id'],
                'district_id' => ['required','exists:districts,id'],
                'category_id' => ['required','exists:categories,id'],
                'pub_type_id' => ['required','exists:pub_types,id'],
                'price'       => ['nullable','numeric'],
                'bathroom'    => ['nullable','integer'],
                'surface'     => ['nullable','numeric'],
                'advance'     => ['nullable','numeric'],
                'deposit'     => ['nullable','numeric'],
                'description' => ['nullable','string'],
                'visit'       => ['nullable','numeric'],
                'offer_type'  => ['required','in:rent,sale'],
                'is_active'   => ['boolean'],
                'attributes'  => ['array'],
                'attributes.*'=> ['exists:attributes,id'],
                'images.*'    => ['image','mimes:jpg,jpeg,png','max:2048'],
                'phone1'      => ['nullable','string','max:20'], // ajouté
                'phone2'      => ['nullable','string','max:20'], // ajouté
            ],
            [
                'country_id.required'  => 'Veuillez sélectionner un pays.',
                'country_id.exists'    => 'Le pays sélectionné est invalide.',
                'town_id.required'     => 'Veuillez sélectionner une ville.',
                'town_id.exists'       => 'La ville sélectionnée est invalide.',
                'district_id.required' => 'Veuillez sélectionner un quartier.',
                'district_id.exists'   => 'Le quartier sélectionné est invalide.',
                'category_id.required' => 'Veuillez sélectionner une catégorie.',
                'category_id.exists'   => 'La catégorie sélectionnée est invalide.',
                'pub_type_id.required' => 'Veuillez sélectionner un type de publication.',
                'pub_type_id.exists'   => 'Le type de publication sélectionné est invalide.',
                'offer_type.required'  => 'Veuillez indiquer si c’est une offre de location ou de vente.',
                'offer_type.in'        => 'Le type d’offre doit être "rent" ou "sale".',
                'images.*.image'       => 'Chaque fichier doit être une image.',
                'images.*.mimes'       => 'Seuls les formats JPG et PNG sont acceptés.',
                'images.*.max'         => 'Chaque image doit être inférieure à 2 Mo.',
                'phone1.max'           => 'Le numéro 1 ne peut pas dépasser 20 caractères.',
                'phone2.max'           => 'Le numéro 2 ne peut pas dépasser 20 caractères.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $publication = Publication::create($validated);

        if (!empty($validated['attributes'] ?? [])) {
            $publication->attributes()->sync($validated['attributes']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('LaCasa/pub'), $imageName);

                $publication->images()->create([
                    'path' => 'LaCasa/pub/' . $imageName
                ]);
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Publication créée avec succès ✅',
            'publication' => $publication->load(['country','town','district','category','pubType','attributes','images'])
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info('📩 UPDATE REÇU', [
            'id' => $id,
            'payload' => $request->all()
        ]);
        $publication = Publication::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'country_id'  => ['required', 'exists:countries,id'],
                'town_id'     => ['required', 'exists:towns,id'],
                'district_id' => ['required', 'exists:districts,id'],
                'category_id' => ['required', 'exists:categories,id'],
                'pub_type_id' => ['required', 'exists:pub_types,id'],
                'price'       => ['nullable', 'numeric'],
                'bathroom'    => ['nullable', 'integer'],
                'surface'     => ['nullable', 'numeric'],
                'advance'     => ['nullable', 'numeric'],
                'deposit'     => ['nullable', 'numeric'],
                'description' => ['nullable', 'string'],
                'visit'       => ['nullable', 'numeric'],
                'offer_type'  => ['required', 'in:rent,sale'],
                'is_active'   => ['boolean'],
                'attributes'  => ['array'],
                'attributes.*'=> ['exists:attributes,id'],
                // 🔥 images deviennent optionnelles
                'images.*'    => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
                'phone1'      => ['nullable','string','max:20'],
                'phone2'      => ['nullable','string','max:20'],
            ],
            [
                'country_id.required'  => 'Veuillez sélectionner un pays.',
                'country_id.exists'    => 'Le pays sélectionné est invalide.',
                'town_id.required'     => 'Veuillez sélectionner une ville.',
                'town_id.exists'       => 'La ville sélectionnée est invalide.',
                'district_id.required' => 'Veuillez sélectionner un quartier.',
                'district_id.exists'   => 'Le quartier sélectionné est invalide.',
                'category_id.required' => 'Veuillez sélectionner une catégorie.',
                'category_id.exists'   => 'La catégorie sélectionnée est invalide.',
                'pub_type_id.required' => 'Veuillez sélectionner un type de publication.',
                'pub_type_id.exists'   => 'Le type de publication sélectionné est invalide.',
                'offer_type.required'  => 'Veuillez indiquer si c’est une offre de location ou de vente.',
                'offer_type.in'        => 'Le type d’offre doit être "rent" ou "sale".',
                'images.*.image'       => 'Chaque fichier doit être une image.',
                'images.*.mimes'       => 'Seuls les formats JPG et PNG sont acceptés.',
                'images.*.max'         => 'Chaque image doit être inférieure à 2 Mo.',
                'phone1.max'           => 'Le numéro 1 ne peut pas dépasser 20 caractères.',
                'phone2.max'           => 'Le numéro 2 ne peut pas dépasser 20 caractères.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        // ✅ Mise à jour des champs principaux
        $publication->update($validated);

        // ✅ Mise à jour des attributs
        if (isset($validated['attributes'])) {
            $publication->attributes()->sync($validated['attributes']);
        }

        // ✅ Suppression et remplacement des images uniquement si de nouvelles images sont envoyées
        if ($request->hasFile('images')) {
            foreach ($publication->images as $img) {
                $imagePath = public_path($img->path);
                if (file_exists($imagePath)) unlink($imagePath);
                $img->delete();
            }

            foreach ($request->file('images') as $image) {
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('LaCasa/pub'), $imageName);

                $publication->images()->create([
                    'path' => 'LaCasa/pub/' . $imageName
                ]);
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Publication mise à jour avec succès ✅',
            'publication' => $publication->load(['country','town','district','category','pubType','attributes','images'])
        ]);
    }



    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);

        // Suppression des images associées
        foreach ($publication->images as $img) {
            $imagePath = public_path($img->path);
            if (file_exists($imagePath)) unlink($imagePath);
            $img->delete();
        }

        $publication->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Publication supprimée avec succès ✅'
        ]);
    }

}

