<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function PubType()
    {
        return $this->hasMany(PubType::class);
    }

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }
}
