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
        'phone1',
        'phone2',
        'is_active',
        'profile_image',
        'facebook_link',
        'tiktok_link',
        'whatsapp_link',
        'is_verified',
        'certify_payment_status'
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

    protected $appends = ['role_name'];

    public function getRoleNameAttribute()
    {
        switch ($this->role) {
            case 1:
                return 'Super Admin';
            case 2:
                return 'Admin';
            case 3:
                return 'Client';
            default:
                return 'Inconnu';
        }
    }

    public function isAgency(): bool
    {
        return $this->user_type === 2;
    }

    public function isPerson(): bool
    {
        return $this->user_type === 1;
    }

    public function publications()
    {
        return $this->hasMany(Publication::class);
    }

}
