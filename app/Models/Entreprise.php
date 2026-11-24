<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'email',
        'telephone',
        'adresse',
        'verification_status',
        'verification_note',
        'numero_enregistrement',
        'numero_autorisation',
        'verified_at',
        'verified_by',
        'subscription_status',
        'subscription_plan',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EntrepriseDocument::class);
    }

    public function platformAuditLogs(): HasMany
    {
        return $this->hasMany(PlatformAuditLog::class);
    }
}
