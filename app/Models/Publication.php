<?php

// app/Models/Publication.php
namespace App\Models;

use App\Models\Town;
use App\Models\Country;
use App\Models\PubType;
use App\Models\Attribut;
use App\Models\Category;
use App\Models\District;
use App\Models\PublicationImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id','town_id','district_id','category_id','pub_type_id','price','bathroom','surface',
        'advance','deposit','description','visit','offer_type','is_active','phone1','phone2'
    ];

    public function country()  { return $this->belongsTo(Country::class); }
    public function town()     { return $this->belongsTo(Town::class); }
    public function district() { return $this->belongsTo(District::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function pubType()  { return $this->belongsTo(PubType::class); }

    public function attributes() {
        return $this->belongsToMany(
            Attribut::class,        // modèle lié
            'publication_attribute', // table pivot
            'publication_id',        // clé étrangère vers Publication
            'attribute_id'           // clé étrangère vers Attribut
        );
    }

    public function images() {
        return $this->hasMany(PublicationImage::class);
    }
}

