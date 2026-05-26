<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['pharmacie', 'clinique'])],
            'adresse' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string', 'max:30'],
        ];
    }
}
