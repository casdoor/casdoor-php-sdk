<?php

declare(strict_types=1);

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\Token;

class TokenTest extends TestCase
{
    public function testGetOAuthToken()
    {

        $endpoint = 'http://127.0.0.1:8000';
        $clientId = 'c64b12723aefb65a88ce';
        $clientSecret = 'c0c9d483a87332751b2564635765d71c9f6a2e83';
        $code = "e3bc886294f2b43b8a1a";

        $token = new Token();

        $accessToken = $token->getOAuthToken($code, '');

        $this->assertInstanceOf(\League\OAuth2\Client\Token\AccessTokenInterface::class, $accessToken);

        $this->assertIsString($accessToken->getToken());
    }
}
