<?php

namespace App\Documentation\Requests\Users;

/**
 * @OA\Schema(
 *     schema="FindUserRequest",
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="123.456.789-09",
 *         description="Deve ser um CPF válido (com ou sem formatação)"
 *     ),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         @OA\Property(
 *             property="cpf",
 *             type="array",
 *             @OA\Items(type="string", example="O CPF fornecido é inválido")
 *         )
 *     )
 * )
 */

class FindUserRequest {}
