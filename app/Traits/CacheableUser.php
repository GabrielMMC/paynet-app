<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;
use App\Models\User;

//FIXME: Lembrar de configurar o arquivo do redis nos containers do docker
trait CacheableUser
{
    public static function getUserFromCache(string $cpf): ?array
    {
        $key = self::getUserCacheKey($cpf);
        $data = Redis::get($key);

        return $data ? json_decode($data, true) : null;
    }

    public static function cacheUserData(string $cpf, User $userPayload): void
    {
        $key = self::getUserCacheKey($cpf);
        Redis::setex($key, self::getCacheTtl(), json_encode($userPayload));
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
