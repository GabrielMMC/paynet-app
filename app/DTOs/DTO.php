<?php

namespace App\DTOs;

class DTO
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public static function fromArray(array $data): self
    {
        return new self(...$data);
    }

    public function toArrayCastSnakeCase(): array
    {
        $vars = get_object_vars($this);
        $snakeVars = [];

        foreach ($vars as $key => $value) {
            $snakeKey = \Illuminate\Support\Str::snake($key);
            $snakeVars[$snakeKey] = $value;
        }

        return $snakeVars;
    }
}
