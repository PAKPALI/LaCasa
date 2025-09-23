<?php

// app/Http/Controllers/PublicationController.php
namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::with([
            'pubType:id,name',
            'category:id,name',      // 🔹 ajout de la relation catégorie
            'district:id,name',
            'town:id,name',
            'country:id,name',
            'images'
        ])->get();

        $formatted = $publications->map(function ($pub) {
            return [
                'id' => $pub->id,
                'title' => $pub->pubType->name ?? 'Type inconnu',         // nom du type de publication
                'price' => $pub->price,
                'bathroom' => $pub->bathroom,
                'surface' => $pub->surface,
                'advance' => $pub->advance,
                'deposit' => $pub->deposit,
                'visit' => $pub->visit,
                'offer_type' => $pub->offer_type,
                'is_active' => $pub->is_active,
                'category_name' => $pub->category->name ?? 'Catégorie inconnue', // nom de la catégorie
                'district_name' => $pub->district->name ?? 'Non défini',
                'town_name' => $pub->town->name ?? 'Non défini',
                'country_name' => $pub->country->name ?? 'Non défini',
                'images' => $pub->images->map(fn($img) => '/'.$img->path) // public path
            ];
        });

        return response()->json($formatted);
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
                'images.*'    => ['image','mimes:jpg,jpeg,png','max:2048']
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
                'images.*.max'         => 'Chaque image doit être inférieure à 2 Mo.'
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
        $publication = Publication::findOrFail($id);

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
                'images.*'    => ['required','image','mimes:jpg,jpeg,png','max:2048']
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
                'images.*.required'    => 'Veuillez ajouter au moins une image.',
                'images.*.image'       => 'Chaque fichier doit être une image.',
                'images.*.mimes'       => 'Seuls les formats JPG et PNG sont acceptés.',
                'images.*.max'         => 'Chaque image doit être inférieure à 2 Mo.'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();

        // Mise à jour de la publication
        $publication->update($validated);

        // Mise à jour des attributs
        if (isset($validated['attributes'])) {
            $publication->attributes()->sync($validated['attributes']);
        }

        // 🔥 Suppression de toutes les anciennes images
        foreach ($publication->images as $img) {
            $imagePath = public_path($img->path);
            if (file_exists($imagePath)) unlink($imagePath);
            $img->delete();
        }

        // 🔥 Ajout des nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = \Str::uuid() . '.' . $image->getClientOriginalExtension();
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

