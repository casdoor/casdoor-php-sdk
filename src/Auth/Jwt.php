<?php

declare(strict_types=1);

namespace Casdoor\Auth;

/**
 * Class Jwt.
 *
 * @author ab1652759879@gmail.com
 */
class Jwt
{
    /**
     * Parse json web token
     *
     * @param string $token
     *
     * @return array
     */
    public function parseJwtToken(string $token): array
    {
        $res = base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1])));
        $res = json_decode($res, true);
        return $res;
    }
}
