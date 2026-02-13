<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Publication;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class PublicationController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $manager = new ImageManager(new Driver());
    }

    public function index(Request $request)
    {
        $query = Publication::with([
            'user:id,name,profile_image,user_type,is_verified,phone1,phone2',
            'pubType:id,name',
            'category:id,name',
            'district:id,name',
            'town:id,name',
            'country:id,name',
            'images',
            'attributes:id,name'
        ])->where('is_active',true)->latest();

        // 🔹 FILTRAGE DYNAMIQUE
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('town_id')) {
            $query->where('town_id', $request->town_id);
        }

        if ($request->filled('district_id')) {
            $districtIds = is_array($request->district_id) 
                ? $request->district_id 
                : [$request->district_id];

            $query->whereIn('district_id', $districtIds);
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

        if ($request->filled('price_period')) {
            $query->where('price_period', $request->price_period);
        }

        if ($request->filled('price1') && $request->filled('price2')) {
            $query->whereBetween('price', [$request->price1, $request->price2]);
        } elseif ($request->filled('price1')) {
            $query->where('price', $request->price1);
        } elseif ($request->filled('price2')) {
            $query->where('price', $request->price2);
        }

        if ($request->filled('offer_type')) {
            if ($request->offer_type === 'sell') {
                $query->where('offer_type', 'sale');
            }

            if ($request->offer_type === 'rent') {
                $query->where('offer_type', 'rent');
            }
        }

        // 🔹 LIMITATION AU CHARGEMENT INITIAL
        if ($request->filled('limit')) {
            $query->latest()->take($request->limit);
        }

        // log::info('🔍 FILTRAGE DES PUBLICATIONS', $request->all());
        if ($request->filled('code')) {
            $query->where('code', $request->code);
        }

        if ($request->has('user_only')) {
            $query->where('user_id', auth()->id());
        }

        $perPage = $request->per_page ?? 20;
        $publications = $query->paginate($perPage);

        $formatted = $publications->getCollection()->map(function ($pub) {
            $defaultUserImage = $pub->user && $pub->user->user_type == 2 ?  'https://cdn-icons-png.flaticon.com/512/3135/3135715.png'   // agence
        : 'https://cdn-icons-png.flaticon.com/512/149/149071.png'; 
            return [
                'id' => $pub->id,
                'code' => $pub->code,
                'title' => $pub->pubType->name ?? 'Type inconnu',
                'price_period' => $pub->price_period,
                'price' => $pub->price,
                'commission' => $pub->commission,
                'bathroom' => $pub->bathroom,
                'surface' => $pub->surface,
                'advance' => $pub->advance,
                'deposit' => $pub->deposit,
                'description' => $pub->description,
                'visit' => $pub->visit,
                'offer_type' => $pub->offer_type,
                'is_active' => $pub->is_active,
                'created_at' => $pub->created_at,
                'category_name' => $pub->category->name ?? 'Catégorie inconnue',
                'district_name' => $pub->district->name ?? 'Non défini',
                'town_name' => $pub->town->name ?? 'Non défini',
                'country_name' => $pub->country->name ?? 'Non défini',
                // 'images' => $pub->images->map(fn($img) => '/'.$img->path),
                'images' => $pub->images->map(fn ($img) => [
                    'list'   => $img->list_url,
                    'detail' => $img->detail_url,
                ]),
                'phone1' => ($pub->phone1 && $pub->phone1 !== 'null') ? $pub->phone1 : null,
                'phone2' => ($pub->phone2 && $pub->phone2 !== 'null') ? $pub->phone2 : null,
                'attributes' => $pub->attributes->map(fn($attr) => ['id' => $attr->id, 'name' => $attr->name]),
                'user' => $pub->user ? [
                    'id' => $pub->user->id,
                    'name' => $pub->user->name,
                    'profile_image' => $pub->user->profile_image ?? $defaultUserImage,
                    'user_type' => $pub->user->user_type,
                    'is_verified' => $pub->user->is_verified,
                    'phone1' => $pub->user->phone1,
                    'phone2' => $pub->user->phone2,
                ] : [
                    'name' => 'Utilisateur inconnu',
                    'profile_image' => $defaultUserImage,
                    'user_type' => null,
                    'phone1' => null,
                    'phone2' => null,
                ],
                
            ];
        });

        return response()->json([
            'data' => $formatted,
            'current_page' => $publications->currentPage(),
            'last_page' => $publications->lastPage(),
            'per_page' => $publications->perPage(),
            'total' => $publications->total(),
        ]);
    }

    public function getMyPublication(Request $request)
    {
        $query = Publication::with([
            'user:id,name,profile_image,user_type,phone1,phone2',
            'pubType:id,name',
            'category:id,name',
            'district:id,name',
            'town:id,name',
            'country:id,name',
            'images',
            'attributes:id,name'
        ])->latest();

        // 🔹 LIMITATION AU CHARGEMENT INITIAL
        if ($request->filled('limit')) {
            $query->latest()->take($request->limit);
        }
        // Mes publications uniquement
        // Log::info('Paramètre user_only présent ?', ['user_only' => $request->has('user_only')]);

        $query->where('user_id', auth()->id());

        $publications = $query->get();

        $formatted = $publications->map(function ($pub) {
            $defaultUserImage = $pub->user && $pub->user->user_type == 2 ?  'https://cdn-icons-png.flaticon.com/512/3135/3135715.png'   // agence
        : 'https://cdn-icons-png.flaticon.com/512/149/149071.png'; 
            return [
                'id' => $pub->id,
                'code' => $pub->code,
                'title' => $pub->pubType->name ?? 'Type inconnu',
                'price_period' => $pub->price_period,
                'price' => $pub->price,
                'commission' => $pub->commission,
                'bathroom' => $pub->bathroom,
                'surface' => $pub->surface,
                'advance' => $pub->advance,
                'deposit' => $pub->deposit,
                'description' => $pub->description,
                'visit' => $pub->visit,
                'offer_type' => $pub->offer_type,
                'is_active' => $pub->is_active,
                'created_at' => $pub->created_at,
                'category_name' => $pub->category->name ?? 'Catégorie inconnue',
                'district_name' => $pub->district->name ?? 'Non défini',
                'town_name' => $pub->town->name ?? 'Non défini',
                'country_name' => $pub->country->name ?? 'Non défini',
                'images' => $pub->images->map(fn ($img) => [
                    'list'   => $img->list_url,
                    'detail' => $img->detail_url,
                ]),
                'phone1' => ($pub->phone1 && $pub->phone1 !== 'null') ? $pub->phone1 : null,
                'phone2' => ($pub->phone2 && $pub->phone2 !== 'null') ? $pub->phone2 : null,
                'attributes' => $pub->attributes->map(fn($attr) => ['id' => $attr->id, 'name' => $attr->name]),
                'user' => $pub->user ? [
                    'id' => $pub->user->id,
                    'name' => $pub->user->name,
                    'profile_image' => $pub->user->profile_image ?? $defaultUserImage,
                    'user_type' => $pub->user->user_type,
                    'is_verified' => $pub->user->is_verified,
                    'phone1' => $pub->user->phone1,
                    'phone2' => $pub->user->phone2,
                ] : [
                    'name' => 'Utilisateur inconnu',
                    'profile_image' => $defaultUserImage,
                    'user_type' => null,
                    'phone1' => null,
                    'phone2' => null,
                ],
                
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

        // Gestion images (URLs déjà calculées grâce à $appends dans PublicationImage)
        $images = $publication->images->map(fn($img) => [
            'id'     => $img->id,
            'thumb'  => $img->thumb,
            'medium' => $img->medium,
            'large'  => $img->large,
            'list'   => $img->list_url,
            'detail' => $img->detail_url,
        ]);

        // Attributs
        $attributes = $publication->attributes->map(fn($a) => [
            'id' => $a->id,
            'name' => $a->name,
        ]);

        return response()->json([
            'id' => $publication->id,
            'country_id' => $publication->country_id,
            'town_id' => $publication->town_id,
            'district_id' => $publication->district_id,
            'category_id' => $publication->category_id,
            'pub_type_id' => $publication->pub_type_id,
            'price_period' => $publication->price_period,
            'price' => $publication->price,
            'commission' => $publication->commission,
            'bathroom' => $publication->bathroom,
            'surface' => $publication->surface,
            'advance' => $publication->advance,
            'deposit' => $publication->deposit,
            'description' => $publication->description,
            'visit' => $publication->visit,
            'offer_type' => $publication->offer_type,
            'is_active' => $publication->is_active,
            'phone1' => $publication->phone1,
            'phone2' => $publication->phone2,
            'images' => $images,
            'attributes' => $attributes,
            // Optionnel : infos lisibles côté modal
            'country_name' => $publication->country->name ?? null,
            'town_name' => $publication->town->name ?? null,
            'district_name' => $publication->district->name ?? null,
            'category_name' => $publication->category->name ?? null,
            'pub_type_name' => $publication->pubType->name ?? null,
        ]);
    }

    // 🔹 STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id'  => ['required','exists:countries,id'],
            'town_id'     => ['required','exists:towns,id'],
            'district_id' => ['required','exists:districts,id'],
            'category_id' => ['required','exists:categories,id'],
            'pub_type_id' => ['required','exists:pub_types,id'],
            'price_period' => ['nullable','in:hour,day,week,month'],
            'price'       => ['nullable','numeric'],
            'commission'  => ['nullable','numeric'],
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
            'images.*'    => ['image','mimes:jpg,jpeg,png,webp','max:10240'],
            'phone1'      => ['nullable','string','max:20'],
            'phone2'      => ['nullable','string','max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $validated['is_active'] ?? true;

        $publication = Publication::create($validated);

        if (!empty($validated['attributes'] ?? [])) {
            $publication->attributes()->sync($validated['attributes']);
        }

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());

            foreach ($request->file('images') as $image) {

                $uuid = Str::uuid()->toString();

                // Taille du fichier en octets
                $size = $image->getSize();

                // 3 Mo
                $limit = 3 * 1024 * 1024;

                // Chemins
                $thumb  = "publications/thumb/$uuid.webp";
                $medium = "publications/medium/$uuid.webp";
                $large  = "publications/large/$uuid.webp";

                if ($size <= $limit) {

                    // ✅ PETITE IMAGE → on garde la qualité max
                    $img = $manager->read($image)->toWebp(90);

                    Storage::disk('public')->put($large, $img);

                    // On pointe tout vers la même image
                    $thumb  = $large;
                    $medium = $large;
                } else {

                    // 🔥 GROSSE IMAGE → optimisation intelligente

                    // THUMB
                    Storage::disk('public')->put(
                        $thumb,
                        $manager->read($image)
                            ->scale(width: 400)
                            ->toWebp(70)
                    );

                    // MEDIUM
                    Storage::disk('public')->put(
                        $medium,
                        $manager->read($image)
                            ->scale(width: 800)
                            ->toWebp(80)
                    );

                    // LARGE
                    Storage::disk('public')->put(
                        $large,
                        $manager->read($image)
                            ->scale(width: 1200)
                            ->toWebp(85)
                    );
                }

                $publication->images()->create([
                    'thumb'  => $thumb,
                    'medium' => $medium,
                    'large'  => $large,
                ]);
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Publication créée avec succès ✅',
            'publication' => $publication->load(['country','town','district','category','pubType','attributes','images'])
        ]);
    }

    // 🔹 UPDATE
    public function update(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'country_id'   => ['required','exists:countries,id'],
            'town_id'      => ['required','exists:towns,id'],
            'district_id'  => ['required','exists:districts,id'],
            'category_id'  => ['required','exists:categories,id'],
            'pub_type_id'  => ['required','exists:pub_types,id'],
            'price_period' => ['nullable','in:day,week,month'],
            'price'        => ['nullable','numeric'],
            'commission'   => ['nullable','numeric'],
            'bathroom'     => ['nullable','integer'],
            'surface'      => ['nullable','numeric'],
            'advance'      => ['nullable','numeric'],
            'deposit'      => ['nullable','numeric'],
            'description'  => ['nullable','string'],
            'visit'        => ['nullable','numeric'],
            'offer_type'   => ['required','in:rent,sale'],
            'is_active'    => ['boolean'],
            'attributes'   => ['array'],
            'attributes.*' => ['exists:attributes,id'],
            'images.*'     => ['image','mimes:jpg,jpeg,png,webp','max:10240'],
            'existing_images' => ['array'],
            'existing_images.*' => ['integer','exists:publication_images,id'],
            'phone1'       => ['nullable','string','max:20'],
            'phone2'       => ['nullable','string','max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $validated['is_active'] = $validated['is_active'] ?? true;

        // Ancien état
        $wasInactive = ! $publication->is_active;

        // Nouvel état (par défaut true si non envoyé)
        $newIsActive = $validated['is_active'] ?? true;

        // Si la publication était inactive et devient active → date de réactivation
        if ($wasInactive && $newIsActive) {
            $validated['reactivated_at'] = Carbon::now();
        }

        // 🔹 Met à jour les données principales
        $publication->update($validated);

        // 🔹 Met à jour les attributs
        if (!empty($validated['attributes'] ?? [])) {
            $publication->attributes()->sync($validated['attributes']);
        }

        // 🔹 Gestion des images
        $manager = new ImageManager(new Driver());

        $existingImages = $validated['existing_images'] ?? [];

        // Supprime les images qui ne sont plus dans existing_images
        foreach ($publication->images as $img) {
            if (!in_array($img->id, $existingImages)) {
                foreach (['thumb','medium','large'] as $size) {
                    if ($img->$size && Storage::disk('public')->exists($img->$size)) {
                        Storage::disk('public')->delete($img->$size);
                    }
                }
                $img->delete();
            }
        }

        // Ajoute les nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uuid = Str::uuid()->toString();

                $sizeFile = $image->getSize();
                $limit = 3 * 1024 * 1024;

                $thumb  = "publications/thumb/$uuid.webp";
                $medium = "publications/medium/$uuid.webp";
                $large  = "publications/large/$uuid.webp";

                if ($sizeFile <= $limit) {
                    $img = $manager->read($image)->toWebp(90);
                    Storage::disk('public')->put($large, $img);
                    $thumb  = $large;
                    $medium = $large;
                } else {
                    Storage::disk('public')->put($thumb, $manager->read($image)->scale(width: 400)->toWebp(70));
                    Storage::disk('public')->put($medium, $manager->read($image)->scale(width: 800)->toWebp(80));
                    Storage::disk('public')->put($large, $manager->read($image)->scale(width: 1200)->toWebp(85));
                }

                $publication->images()->create([
                    'thumb'  => $thumb,
                    'medium' => $medium,
                    'large'  => $large,
                ]);
            }
        }

        return response()->json([
            'status'      => true,
            'message'     => 'Publication mise à jour avec succès ✅',
            'publication' => $publication->load(['country','town','district','category','pubType','attributes','images'])
        ]);
    }

    // 🔹 DESTROY
    public function destroy($id)
    {
        $publication = Publication::findOrFail($id);

        foreach ($publication->images as $img) {
            foreach (['thumb','medium','large'] as $size) {
                if ($img->$size && Storage::exists("public/{$img->$size}")) {
                    Storage::delete("public/{$img->$size}");
                }
            }
            $img->delete();
        }

        $publication->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Publication supprimée avec succès ✅'
        ]);
    }
}
