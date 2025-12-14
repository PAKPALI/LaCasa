<?php

// app/Models/PublicationImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PublicationImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'publication_id',
        'path',
        'thumb',
        'medium',
        'large'
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * Image pour les listes (rapide)
     */
    public function getListUrlAttribute()
    {
        // Nouvelle image optimisée
        if ($this->thumb) {
            // return asset('storage/'.$this->thumb);
            return $this->thumb ? Storage::url($this->thumb) : null;
        }

        // Ancienne image
        return asset($this->path);
    }

    /**
     * Image pour le détail
     */
    public function getDetailUrlAttribute()
    {
        if ($this->medium) {
            // return asset('storage/'.$this->medium);
            return $this->medium ? Storage::url($this->medium) : null;
        }

        return asset($this->path);
    }
}