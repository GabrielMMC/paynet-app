<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class ValidCep implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cep = $this->sanitize($value);

        if (!$this->hasValidLength($cep)) {
            $fail('O CEP deve conter 8 dígitos.');
            return;
        }

        if (!$this->containsOnlyDigits($cep)) {
            $fail('O CEP deve conter apenas números.');
            return;
        }
    }

    private function sanitize(string $value): string
    {
        return str_replace(['.', '-', ' ', '/'], '', $value);
    }

    private function hasValidLength(string $cep): bool
    {
        return strlen($cep) === 8;
    }

    private function containsOnlyDigits(string $cep): bool
    {
        return ctype_digit($cep);
    }
}
