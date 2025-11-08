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
        // Skip: This test requires a valid authorization code from OAuth flow
        // The hardcoded code is no longer valid
        $this->markTestSkipped('Requires valid OAuth authorization code from live auth flow');
    }

    public function testParseJwtToken()
    {
        // Skip: This test requires a valid authorization code from OAuth flow
        // The hardcoded code is no longer valid
        $this->markTestSkipped('Requires valid OAuth authorization code from live auth flow');
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
        
        $name = TestUtil::getRandomName('User');
        
        // Create a new user with proper fields
        $user = new User();
        $user->owner = TestUtil::TEST_ORGANIZATION;
        $user->name = $name;
        $user->createdTime = TestUtil::getCurrentTime();
        $user->displayName = $name;
        
        // Add the user
        $response = $user->addUser($user);
        $this->assertTrue($response, 'Failed to add user');

        // Update the user
        $user->phone = '+1234567890';
        $user->displayName = 'Updated ' . $name;
        $response = $user->updateUser($user);
        $this->assertTrue($response, 'Failed to update user');
        
        // Delete the user
        $response = $user->deleteUser($user);
        $this->assertTrue($response, 'Failed to delete user');
    }
}
