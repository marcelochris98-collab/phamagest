<?php

namespace App\Support;

use App\Models\Entreprise;
use LogicException;

class CurrentEntreprise
{
    private ?Entreprise $entreprise = null;

    public function set(Entreprise $entreprise): void
    {
        $this->entreprise = $entreprise;
    }

    public function get(): Entreprise
    {
        return $this->entreprise
            ?? throw new LogicException('No active company has been resolved for this request.');
    }
}
