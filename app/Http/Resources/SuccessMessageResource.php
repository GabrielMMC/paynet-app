<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class SuccessMessageResource extends JsonResource
{
    public function __construct(string $message)
    {
        parent::__construct(['message' => $message]);
    }

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
