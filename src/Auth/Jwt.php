<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Firebase\JWT\JWT as firebase_jwt;

/**
 * Class Jwt.
 *
 * @author ab1652759879@gmail.com
 */
class Jwt
{
    public function parseJwtToken(string $token): object
    {
        $authConfig = $GLOBALS['authConfig'];

        return firebase_jwt::decode($token, $authConfig->jwtSecret, ['RS256']);
    }
}
