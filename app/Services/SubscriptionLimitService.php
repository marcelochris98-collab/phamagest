<?php

namespace App\Services;

use App\Models\Entreprise;
use App\Models\Medicament;

class SubscriptionLimitService
{
    public function canAddUser(Entreprise $entreprise): bool
    {
        $limit = $entreprise->subscriptionPlan()->firstOrFail()->user_limit;

        return $limit === null || $entreprise->users()->count() < $limit;
    }

    public function canAddProduct(Entreprise $entreprise): bool
    {
        $limit = $entreprise->subscriptionPlan()->firstOrFail()->product_limit;

        return $limit === null || Medicament::count() < $limit;
    }
}
