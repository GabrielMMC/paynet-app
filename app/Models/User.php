<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'cpf',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class);
    }

    public function financialProfile(): HasOne
    {
        return $this->hasOne(UserFinancialProfile::class);
    }

    public function risk(): HasOneThrough
    {
        return $this->hasOneThrough(
            Risk::class,
            UserFinancialProfile::class,
            'user_id',
            'id',
            'id',
            'risk_id'
        );
    }

    public function situation(): HasOneThrough
    {
        return $this->hasOneThrough(
            Situation::class,
            UserFinancialProfile::class,
            'user_id',
            'id',
            'id',
            'situation_id'
        );
    }
}
