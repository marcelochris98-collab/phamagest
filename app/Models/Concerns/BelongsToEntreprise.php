<?php

namespace App\Models\Concerns;

use App\Models\Entreprise;
use App\Models\Scopes\EntrepriseScope;
use App\Support\CurrentEntreprise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait BelongsToEntreprise
{
    protected static function bootBelongsToEntreprise(): void
    {
        static::addGlobalScope(new EntrepriseScope);

        static::saving(function (Model $model) {
            $entrepriseId = app(CurrentEntreprise::class)->get()->getKey();
            $modelEntrepriseId = $model->getAttribute('entreprise_id');

            if ($model->exists && (string) $model->getRawOriginal('entreprise_id') !== (string) $entrepriseId) {
                throw ValidationException::withMessages([
                    'entreprise_id' => 'Cette donnée ne peut pas être modifiée depuis une autre entreprise.',
                ]);
            }

            if ($modelEntrepriseId !== null && (string) $modelEntrepriseId !== (string) $entrepriseId) {
                throw ValidationException::withMessages([
                    'entreprise_id' => 'La donnée doit appartenir à l’entreprise active.',
                ]);
            }

            $model->setAttribute('entreprise_id', $entrepriseId);

            foreach (static::entrepriseForeignKeys() as $foreignKey => $relatedModel) {
                $relatedId = $model->getAttribute($foreignKey);

                if ($relatedId === null) {
                    continue;
                }

                $belongsToEntreprise = $relatedModel::withoutGlobalScopes()
                    ->whereKey($relatedId)
                    ->where('entreprise_id', $entrepriseId)
                    ->exists();

                if (! $belongsToEntreprise) {
                    throw ValidationException::withMessages([
                        $foreignKey => 'La référence sélectionnée doit appartenir à l’entreprise active.',
                    ]);
                }
            }

            foreach (static::entrepriseMemberForeignKeys() as $foreignKey) {
                $userId = $model->getAttribute($foreignKey);

                if ($userId === null) {
                    continue;
                }

                $isMember = DB::table('entreprise_user')
                    ->where('entreprise_id', $entrepriseId)
                    ->where('user_id', $userId)
                    ->exists();

                if (! $isMember) {
                    throw ValidationException::withMessages([
                        $foreignKey => 'L’utilisateur doit être membre de l’entreprise active.',
                    ]);
                }
            }
        });

        static::deleting(function (Model $model) {
            $entrepriseId = app(CurrentEntreprise::class)->get()->getKey();

            if ((string) $model->getRawOriginal('entreprise_id') !== (string) $entrepriseId) {
                throw ValidationException::withMessages([
                    'entreprise_id' => 'Cette donnée ne peut pas être supprimée depuis une autre entreprise.',
                ]);
            }
        });
    }

    protected static function entrepriseForeignKeys(): array
    {
        return [];
    }

    protected static function entrepriseMemberForeignKeys(): array
    {
        return [];
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }
}
