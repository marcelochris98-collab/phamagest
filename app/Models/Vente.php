<?php

namespace App\Models;

use App\Enums\VenteStatut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'total',
        'statut',
        'date_vente',
    ];

    protected function casts(): array
    {
        return [
            'statut' => VenteStatut::class,
            'total' => 'decimal:2',
            'date_vente' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VenteItem::class);
    }
}
