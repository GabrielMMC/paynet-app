<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * @OA\Info(
     *     title="Paynet API",
     *     version="1.0.0"
     * )
     * 
     * @OA\Schema(
     *     schema="ProcessUserRequest",
     *     required={"cpf", "cep", "email"},
     *     @OA\Property(property="cpf", type="string", example="123.456.789-09", description="CPF do usuário"),
     *     @OA\Property(property="cep", type="string", example="01001000", description="CEP do endereço"),
     *     @OA\Property(property="email", type="string", format="email", example="usuario@example.com", description="E-mail do usuário")
     * )
     * 
     * @OA\Schema(
     *     schema="UserResource",
     *     @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
     *     @OA\Property(property="name", type="string", example="João Silva"),
     *     @OA\Property(property="cpf", type="string", example="123.456.789-09"),
     *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
     *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     * )
     * 
     * @OA\Schema(
     *     schema="ErrorResponse",
     *     @OA\Property(property="message", type="string", example="Falha ao processar usuário, tente novamente mais tarde."),
     *     @OA\Property(property="errors", type="object", example={})
     * )
     * 
     * @OA\PathItem(path="/api/v1/users/process")
     */
}
