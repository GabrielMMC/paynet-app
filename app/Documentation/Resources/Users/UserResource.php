<?php

namespace App\Documentation\Resources\Users;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         example="usuario@example.com"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="João Silva"
 *     ),
 *     @OA\Property(
 *         property="cpf",
 *         type="string",
 *         example="123.456.789-09"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-01-01T12:00:00Z"
 *     )
 * )
 */

class UserResource {}
