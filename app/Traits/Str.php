<?php

namespace App\Traits;

trait Str
{
    public static function sanitizeToNumber(string $value): string
    {
        return preg_replace('/\D/', '', $value ?? '');
    }
}
