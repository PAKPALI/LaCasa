<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id',
        'payment_method',
        'payment_gateway',
        'payment_operator',
        'payment_reference',
        'user_id',
        'amount',
        'currency',
        'status',
        'webhook_payload',
    ];

    protected $casts = [
        'webhook_payload' => 'array',
    ];

    public function user()
    { 
        return $this->belongsTo(User::class);
    }
}
