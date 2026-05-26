<?php

namespace App\Models;

use App\Enums\MouvementStockType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    use HasFactory;

    protected $table = 'mouvements_stock';

    protected $fillable = [
        'medicament_id',
        'type',
        'quantite',
        'motif',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'type' => MouvementStockType::class,
            'quantite' => 'integer',
            'date' => 'datetime',
        ];
    }

    public function medicament(): BelongsTo
    {
        return $this->belongsTo(Medicament::class);
    }
}
