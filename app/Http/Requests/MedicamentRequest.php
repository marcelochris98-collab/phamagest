<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'fournisseur_id' => ['nullable', 'exists:fournisseurs,id'],
        ];
    }
}
