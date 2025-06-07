<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCep;
use App\Rules\ValidCpf;

class ProcessUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cpf'   => ['required', 'string', new ValidCpf],
            'cep'   => ['required', 'string', new ValidCep],
            'email' => ['required', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.required'   => 'O CPF é obrigatório.',
            'cep.required'   => 'O CEP é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'O e-mail deve ser válido.',
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        return [
            $validated['cpf'],
            $validated['cep'],
            $validated['email']
        ];
    }
}
