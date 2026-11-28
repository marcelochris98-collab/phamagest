<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'price_fcfa',
        'user_limit',
        'product_limit',
        'kpis_enabled',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_fcfa' => 'integer',
            'user_limit' => 'integer',
            'product_limit' => 'integer',
            'kpis_enabled' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
