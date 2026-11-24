<?php

namespace App\Models;

use App\Enums\CommandeStatut;
use App\Models\Concerns\BelongsToEntreprise;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    use BelongsToEntreprise, HasFactory;

    protected $fillable = [
        'fournisseur_id',
        'statut',
        'date_commande',
        'date_livraison_prevue',
    ];

    protected function casts(): array
    {
        return [
            'statut' => CommandeStatut::class,
            'date_commande' => 'date',
            'date_livraison_prevue' => 'date',
        ];
    }

    protected static function entrepriseForeignKeys(): array
    {
        return [
            'fournisseur_id' => Fournisseur::class,
        ];
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CommandeItem::class);
    }
}
