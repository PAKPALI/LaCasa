<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs pouvant être remplis en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country_id',
        'town_id',
        'district_id',
        'user_type',
        'role',
        'profile_image', // ← ajouté
    ];


    /**
     * Les attributs cachés lors de la sérialisation.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être typés automatiquement.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ----------------------
    // 🔗 RELATIONS ELOQUENT
    // ----------------------

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // ----------------------
    // 🎯 MÉTHODES UTILES
    // ----------------------

    public function isSuperAdmin(): bool
    {
        return $this->role === 1;
    }

    public function isAdmin(): bool
    {
        return $this->role === 2;
    }

    public function isClient(): bool
    {
        return $this->role === 3;
    }

    public function isAgency(): bool
    {
        return $this->user_type === 2;
    }

    public function isPerson(): bool
    {
        return $this->user_type === 1;
    }
}
