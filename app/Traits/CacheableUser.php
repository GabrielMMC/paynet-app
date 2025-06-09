<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;
use App\Models\User;

trait CacheableUser
{
    public static function getUserFromCache(string $cpf): ?User
    {
        $key = self::getUserCacheKey($cpf);
        $data = Redis::get($key);

        return $data ? self::fillUser($data) : null;
    }

    public static function cacheUserData(string $cpf, User $userPayload): void
    {
        $key = self::getUserCacheKey($cpf);
        Redis::setex($key, self::getCacheTtl(), json_encode($userPayload));
    }

    private static function fillUser(string $data): User
    {
        $user = new User();
        $user->forceFill(json_decode($data, true));

        return $user;
    }

    private static function getUserCacheKey(string $cpf): string
    {
        return "user:data:" . md5($cpf);
    }

    private static function getCacheTtl(): int
    {
        return 86400; // 24h in seconds
    }
}
