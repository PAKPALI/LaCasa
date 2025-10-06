<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'town_id'];

    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function country()
    {
        // Accès rapide au pays via la relation ville → pays
        return $this->town->country();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
