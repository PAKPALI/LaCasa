<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        return response()->json(Country::all());
    }

    public function store(Request $request)
    {
        $country = Country::create($request->only(['name', 'acronym', 'code']));
        return response()->json(['status' => true, 'message' => 'Pays ajouté avec succès', 'data' => $country]);
    }

    public function show(Country $country)
    {
        return response()->json($country);
    }

    public function update(Request $request, Country $country)
    {
        $country->update($request->only(['name', 'acronym', 'code']));
        return response()->json(['status' => true, 'message' => 'Pays modifié avec succès', 'data' => $country]);
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return response()->json(['status' => true, 'message' => 'Pays supprimé avec succès']);
    }
}
