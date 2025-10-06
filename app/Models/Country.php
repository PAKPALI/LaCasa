<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'acronym',
        'code',
    ];
    // App\Models\Country.php
    public function towns()
    {
        return $this->hasMany(Town::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
