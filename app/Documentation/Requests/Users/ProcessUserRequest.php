<?php

namespace App\Documentation\Requests\Users;

/**
 * @OA\Schema(
 *     schema="ProcessUserRequest",
 *     required={"cpf", "cep", "email"},
 *     @OA\Property(
 *         property="cpf",
 *         type="string",
 *         example="123.456.789-09",
 *         description="CPF do usuário (formato XXX.XXX.XXX-XX)"
 *     ),
 *     @OA\Property(
 *         property="cep",
 *         type="string",
 *         example="01001000",
 *         description="CEP do endereço (8 dígitos)"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="usuario@example.com",
 *         description="E-mail válido do usuário"
 *     )
 * )
 */

class ProcessUserRequest {}
