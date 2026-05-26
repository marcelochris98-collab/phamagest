<?php

namespace App\Enums;

enum ClientType: string
{
    case Pharmacie = 'pharmacie';
    case Clinique = 'clinique';

    public function label(): string
    {
        return match ($this) {
            self::Pharmacie => 'Pharmacie',
            self::Clinique => 'Clinique',
        };
    }
}
