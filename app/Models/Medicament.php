<?php

namespace App\Models;

use App\Models\Concerns\BelongsToEntreprise;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicament extends Model
{
    use BelongsToEntreprise, HasFactory;

    protected $fillable = [
        'nom',
        'dci',
        'forme',
        'dosage',
        'quantite_stock',
        'seuil_alerte',
        'date_peremption',
        'fournisseur_id',
    ];

    protected function casts(): array
    {
        return [
            'date_peremption' => 'date',
            'quantite_stock' => 'integer',
            'seuil_alerte' => 'integer',
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

    public function commandeItems(): HasMany
    {
        return $this->hasMany(CommandeItem::class);
    }

    public function venteItems(): HasMany
    {
        return $this->hasMany(VenteItem::class);
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class);
    }

    protected function stockCritique(): Attribute
    {
        return Attribute::get(fn (): bool => $this->quantite_stock <= $this->seuil_alerte);
    }

    protected function peremptionProche(): Attribute
    {
        return Attribute::get(function (): bool {
            if (! $this->date_peremption) {
                return false;
            }

            return $this->date_peremption->lte(now()->addMonths(3));
        });
    }
}
