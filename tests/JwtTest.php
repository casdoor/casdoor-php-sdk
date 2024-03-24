<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\AuthConfig;
use Casdoor\Auth\Jwt;

class JwtTest extends TestCase
{


    public function initConfig()
    {
        $endpoint = 'http://127.0.0.1:8000';
        $clientId = 'c64b12723aefb65a88ce';
        $clientSecret = 'c0c9d483a87332751b2564635765d71c9f6a2e83';
        $jwtSecret = file_get_contents(dirname(__FILE__) . '/public_key.pem');
        $organizationName = 'built-in';
        $applicationName = 'testApp';
        User::initConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName, $applicationName);
    }

    public function testParseJwtToken()
    {
        $this->initConfig();
        
        $token = $this->createTestJwtToken();

        $token = new Token();
        $accessToken = $token->getOAuthToken($this->code, '');
        $token = $accessToken->getToken();

        $jwt = new Jwt();

        $result = $jwt->parseJwtToken($token,  User::$authConfig);

        $this->assertIsArray($result);

    }


}
