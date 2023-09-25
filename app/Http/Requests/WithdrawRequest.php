<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'withdraw' => 'numeric|min:0.01'
        ];
    }

    public function messages()
    {
        return [
            'numeric' => 'O valor de retirada deve ser numérico',
            'min' => 'O valor de retirada mínimo é de um centavo'
        ];
    }  
}
