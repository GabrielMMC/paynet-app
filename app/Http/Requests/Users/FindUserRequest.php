<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCpf;
use App\Traits\Str;

class FindUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cpf' => ['required', 'string', new ValidCpf],
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.string'   => 'O CPF deve ser uma string.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => Str::sanitizeToNumber($this->route('cpf'))
        ]);
    }
}
