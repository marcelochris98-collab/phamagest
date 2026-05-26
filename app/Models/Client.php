<?php

namespace App\Models;

use App\Enums\ClientType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'adresse',
        'telephone',
    ];

    protected function casts(): array
    {
        return [
            'type' => ClientType::class,
        ];
    }

    public function ventes(): HasMany
    {
        return $this->hasMany(Vente::class);
    }
}
