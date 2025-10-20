<?php

namespace App\Models;

use App\Models\Attribut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PubType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id'];

    // Relation vers la catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Attribut()
    {
        return $this->hasMany(Attribut::class);
    }
}
