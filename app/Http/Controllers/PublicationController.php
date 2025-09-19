<?php

// app/Http/Controllers/PublicationController.php
namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicationController extends Controller
{
    public function index() {
        return response()->json(Publication::with(['country','town','district','category','pubType','attributes','images'])->get());
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'country_id'  => 'required|exists:countries,id',
            'town_id'     => 'required|exists:towns,id',
            'district_id' => 'required|exists:districts,id',
            'category_id' => 'required|exists:categories,id',
            'pub_type_id' => 'required|exists:pub_types,id',
            'price'       => 'nullable|numeric',
            'bathroom'    => 'nullable|integer',
            'surface'     => 'nullable|numeric',
            'advance'     => 'nullable|numeric',
            'deposit'     => 'nullable|numeric',
            'description' => 'nullable|string',
            'visit'       => 'nullable|numeric',
            'offer_type'  => 'required|in:rent,sale',
            'is_active'   => 'boolean',
            'attributes'  => 'array',
            'images.*'    => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'message'=>$validator->errors()->first()
            ]);
        }

        $publication = Publication::create($validator->validated());

        if ($request->has('attributes')) {
            $publication->attributes()->sync($request->attributes);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('publications','public');
                $publication->images()->create(['path'=>$path]);
            }
        }

        return response()->json([
            'status'=>true,
            'message'=>'Publication créée avec succès',
            'data'=>$publication->load(['attributes','images'])
        ]);
    }
}

