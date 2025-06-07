<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class ValidCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = $this->sanitize($value);

        if (!$this->hasValidLength($cpf)) {
            $fail('O CPF deve conter 11 dígitos.');
            return;
        }

        if ($this->isAllRepeatedDigits($cpf)) {
            $fail('O CPF não pode conter todos os dígitos iguais.');
            return;
        }

        if (!$this->hasValidVerificationDigits($cpf)) {
            $fail('O CPF informado não é válido.');
            return;
        }
    }

    private function sanitize(string $value): string
    {
        return preg_replace('/\D/', '', $value);
    }

    private function hasValidLength(string $cpf): bool
    {
        return strlen($cpf) === 11;
    }

    private function isAllRepeatedDigits(string $cpf): bool
    {
        return preg_match('/^(\d)\1*$/', $cpf);
    }

    private function hasValidVerificationDigits(string $cpf): bool
    {
        $firstDigit = $this->calculateVerificationDigit($cpf, 9);
        $secondDigit = $this->calculateVerificationDigit($cpf, 10);

        return $cpf[9] == $firstDigit && $cpf[10] == $secondDigit;
    }

    private function calculateVerificationDigit(string $cpf, int $position): int
    {
        $total = 0;
        $multiplier = $position + 1;

        for ($i = 0; $i < $position; $i++) {
            $total += $cpf[$i] * ($multiplier - $i);
        }

        $remainder = $total % 11;
        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
