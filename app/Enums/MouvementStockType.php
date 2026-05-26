<?php

namespace App\Enums;

enum MouvementStockType: string
{
    case Entree = 'entree';
    case Sortie = 'sortie';

    public function label(): string
    {
        return match ($this) {
            self::Entree => 'Entrée',
            self::Sortie => 'Sortie',
        };
    }
}
