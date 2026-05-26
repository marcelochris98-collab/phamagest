<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'fournisseur_id' => ['required', 'exists:fournisseurs,id'],
            'date_commande' => ['required', 'date'],
            'date_livraison_prevue' => ['nullable', 'date', 'after_or_equal:date_commande'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicament_id' => ['required', 'exists:medicaments,id'],
            'items.*.quantite' => ['required', 'integer', 'min:1'],
            'items.*.prix_unitaire' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'items' => 'lignes',
            'items.*.medicament_id' => 'médicament',
            'items.*.quantite' => 'quantité',
            'items.*.prix_unitaire' => 'prix unitaire',
        ];
    }
}
