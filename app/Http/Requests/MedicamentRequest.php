<?php

namespace App\Http\Requests;

use App\Support\CurrentEntreprise;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicamentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'dci' => ['nullable', 'string', 'max:255'],
            'forme' => ['nullable', 'string', 'max:100'],
            'dosage' => ['nullable', 'string', 'max:100'],
            'quantite_stock' => ['required', 'integer', 'min:0'],
            'seuil_alerte' => ['required', 'integer', 'min:0'],
            'date_peremption' => ['nullable', 'date'],
            'fournisseur_id' => [
                'nullable',
                Rule::exists('fournisseurs', 'id')
                    ->where('entreprise_id', app(CurrentEntreprise::class)->get()->getKey()),
            ],
        ];
    }
}
