<?php

namespace App\Documentation\Resources\Users;

/**
 * @OA\Schema(
 *     schema="UserDataResource",
 *     @OA\Property(
 *         property="user", 
 *         ref="#/components/schemas/UserResource"
 *     ),
 *     @OA\Property(
 *         property="address",
 *         ref="#/components/schemas/UserAddressResource"
 *     ),
 *     @OA\Property(
 *         property="financial_profile",
 *         ref="#/components/schemas/UserFinancialProfileResource"
 *     )
 * )
 */

class UserDataResource {}
