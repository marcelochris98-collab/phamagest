<?php

namespace App\Models\Scopes;

use App\Support\CurrentEntreprise;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EntrepriseScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where(
            $model->qualifyColumn('entreprise_id'),
            app(CurrentEntreprise::class)->get()->getKey()
        );
    }
}
