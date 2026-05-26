<?php

namespace App\Enums;

enum CommandeStatut: string
{
    case EnAttente = 'en_attente';
    case Livree = 'livree';
    case Annulee = 'annulee';

    public function label(): string
    {
        return match ($this) {
            self::EnAttente => 'En attente',
            self::Livree => 'Livrée',
            self::Annulee => 'Annulée',
        };
    }
}
