<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\Jwt;
use Casdoor\Auth\Token;
use Casdoor\Auth\User;

class OauthTest extends TestCase
{
    public $code = "e3bc886294f2b43b8a1a";

    public $user;

    public function initConfig()
    {
        $endpoint = TestUtil::TEST_ENDPOINT;
        $clientId = TestUtil::TEST_CLIENT_ID;
        $clientSecret = TestUtil::TEST_CLIENT_SECRET;
        $jwtSecret = TestUtil::TEST_JWT_PUBLIC_KEY;
        $organizationName = TestUtil::TEST_ORGANIZATION;
        $applicationName = TestUtil::TEST_APPLICATION;
        User::initConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName, $applicationName);
    }

    public function testGetOauthToken()
    {
        $this->initConfig();
        $token = new Token();
        $accessToken = $token->getOAuthToken($this->code, '');
        $this->assertIsString($accessToken->getToken());
    }

    public function testParseJwtToken()
    {
        $this->initConfig();
        $token = new Token();
        $accessToken = $token->getOAuthToken($this->code, '');
        $token = $accessToken->getToken();
        $jwt = new Jwt();
        $this->assertIsArray($jwt->parseJwtToken($token, User::$authConfig));
    }

    public function testGetUsers()
    {
        $this->initConfig();
        $users = User::getUsers();
        $this->assertIsArray($users);
    }

    public function testGetUserCount()
    {
        $this->initConfig();
        $count = User::getUserCount(1);
        $this->assertIsInt($count);
    }

    public function testGetUser()
    {
        $this->initConfig();
        $user = User::getUser('admin2');
        $this->assertIsArray($user);
    }

    /**
     * Support PHP 8.0, no error will be reported
     */
    public function testModifyUser()
    {
        $this->initConfig();
        $user = new User();
        $user->name = 'user_hn99qa';
        $response = $user->deleteUser($user);
        $this->assertTrue($response);

        $response = $user->addUser($user);
        $this->assertTrue($response);

        $user->phone = 'phone';
        $user->displayName = 'display name';
        $response = $user->updateUser($user);
        $this->assertTrue($response);
    }
}
