<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAutoEcoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', "unique:auto_ecoles"],
            'gerant_id' => ['reqiored', 'exists:users,id'],
            'permis_list' => ['required', 'array', 'in:AM,A1,A,B,C,D,EB,EC,ED'],
        ];
    }
}
