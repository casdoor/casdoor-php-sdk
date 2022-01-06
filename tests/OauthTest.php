<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\Jwt;
use Casdoor\Auth\Token;
use Casdoor\Auth\User;

class OauthTest extends TestCase
{
    // @todo use Mockery to create the dummy/mock object
    /**
     * server returned authorization code
     */
    // public $code = "e3bc886294f2b43b8a1a";

    // public $user;

    // public function initConfig()
    // {
    //     User::initConfig(
    //         'localhost:8000',
    //         'cb00020ceab4c83751a9',
    //         'd6b7cb7db0c5577a26fb876693d4b5d84e31d62a',
    //         '',
    //         'built-in',
    //         ''
    //     );
    // }

    // public function testGetOauthToken()
    // {
    //     $this->initConfig();
    //     $token = new Token();
    //     $accessToken = $token->getOAuthToken($this->code, '');
    //     $this->assertIsString($accessToken->getToken());
    // }

    // public function testParseJwtToken()
    // {
    //     $this->initConfig();
    //     $token = new Token();
    //     $accessToken = $token->getOAuthToken($this->code, '');
    //     $token = $accessToken->getToken();
    //     $jwt = new Jwt();
    //     $this->assertIsArray($jwt->parseJwtToken($token, User::$authConfig));
    // }

    // public function testGetUsers()
    // {
    //     $this->initConfig();
    //     $users = User::getUsers();
    //     $this->assertIsArray($users);
    // }

    // public function testGetUserCount()
    // {
    //     $this->initConfig();
    //     $count = User::getUserCount('true');
    //     $this->assertIsInt($count);
    // }

    // public function testGetUser()
    // {
    //     $this->initConfig();
    //     $user = User::getUser('admin2');
    //     $this->assertIsArray($user);
    // }

    // public function testModifyUser()
    // {
    //     $this->initConfig();
    //     $this->user->name = 'test_user';
    //     $this->user->deleteUser($this->user);

    //     $response = $this->user->addUser($this->user);
    //     $this->assertTrue($response);

    //     $response = $this->user->deleteUser($this->user);
    //     $this->assertTrue($response);

    //     $response = $this->user->addUser($this->user);
    //     $this->assertTrue($response);

    //     $this->user->phone = 'phone';
    //     $this->user->displayName = 'display name';
    //     $response = $this->user->updateUser($this->user);
    //     $this->assertTrue($response);
    // }
}
