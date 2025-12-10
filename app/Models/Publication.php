<?php

// app/Models/Publication.php
namespace App\Models;

use Carbon\Carbon;
use App\Models\Town;
use App\Models\Country;
use App\Models\PubType;
use App\Models\Attribut;
use App\Models\Category;
use App\Models\District;
use Illuminate\Support\Str;
use App\Models\PublicationImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','country_id','town_id','district_id','category_id','pub_type_id','price',
        'bathroom','surface','advance','deposit','description','visit','offer_type','is_active',
        'phone1','phone2','code','commission','deactivated_at','reactivated_at','price_period'
    ];

    public function user()
    { return $this->belongsTo(User::class);}

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($publication) {
            $publication->code = strtoupper(Str::random(5));
        });
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reactivated_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    // Vérifie si elle doit être désactivée

    public function shouldBeDeactivated()
    {
        $referenceDate = $this->reactivated_at ?? $this->created_at;
        return (bool)$this->is_active && Carbon::now()->greaterThanOrEqualTo(Carbon::parse($referenceDate)->addDays(30));
    }

    public function shouldSendDeletionWarning()
    {
        // On ne prévient que si elle est désactivée mais pas encore supprimée
        if ($this->is_active || !$this->deactivated_at) {
            return false;
        }

        $daysSinceDeactivation = Carbon::parse($this->deactivated_at)->diffInDays(Carbon::now());

        // 25 jours écoulés depuis désactivation → on envoie l’avertissement
        return $daysSinceDeactivation === 25;
    }

    public function shouldBeDeleted()
    {
        // Vérifie que la publication est désactivée
        if ($this->is_active) {
            return false;
        }

        // On prend la date de désactivation comme référence
        $referenceDate = $this->deactivated_at;

        // Si elle n’a pas de date de désactivation, on ne supprime pas
        if (!$referenceDate) {
            return false;
        }

        // Si 30 jours se sont écoulés depuis la désactivation → suppression
        return Carbon::now()->greaterThanOrEqualTo(Carbon::parse($referenceDate)->addDays(30));
    }
}

