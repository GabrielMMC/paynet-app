<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class UserFinancialProfile extends Model
{
    protected $table = 'user_financial_profiles';

    protected $fillable = [
        'risk_id',
        'situation_id',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function risk(): BelongsTo
    {
        return $this->belongsTo(Risk::class);
    }

    public function situation(): BelongsTo
    {
        return $this->belongsTo(Situation::class);
    }
}
