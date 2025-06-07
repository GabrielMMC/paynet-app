<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    protected $table = 'user_addresses';

    protected $fillable = [
        'cep',
        'street',
        'complement',
        'neighborhood',
        'city',
        'state_code',
        'state',
        'region',
        'ibge',
        'gia',
        'ddd',
        'siafi',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
