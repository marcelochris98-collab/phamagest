<?php

namespace App\Http\Requests;

use App\Support\CurrentEntreprise;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')
                    ->where('entreprise_id', app(CurrentEntreprise::class)->get()->getKey()),
            ],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicament_id' => [
                'required',
                Rule::exists('medicaments', 'id')
                    ->where('entreprise_id', app(CurrentEntreprise::class)->get()->getKey()),
            ],
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
