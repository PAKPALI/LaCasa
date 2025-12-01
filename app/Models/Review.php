<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rating',
        'comment',
        'is_visible'
    ];

    // Auteur de l'avis
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Commentaires (admin / réponses)
    public function comments()
    {
        return $this->hasMany(ReviewComment::class)->orderBy('created_at', 'asc');
    }
}
