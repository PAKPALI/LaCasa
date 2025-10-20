<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribut extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'pub_type_id'];
    protected $table = 'attributes';


    public function pubType()
    {
        return $this->belongsTo(PubType::class);
    }

    public function publications()
    {
        return $this->belongsToMany(
            Publication::class,
            'publication_attribute',
            'attribute_id',   // clé locale dans la table pivot
            'publication_id'  // clé étrangère de la table publication
        );
    }

}
