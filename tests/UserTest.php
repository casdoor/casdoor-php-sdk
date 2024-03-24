<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\User;


class UserTest extends TestCase
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

    public function testAddUser()
    {
        $this->initConfig();
        
        $user = new User();
        $user->name = 'testUser';
        $user->owner = 'testOwner';
        $response = $user->addUser($user);
        
        $this->assertTrue($response);
    }

    public function testGetUser()
    {
        
        $this->initConfig();        
        $user = User::getUser('testUser');
        
        $this->assertIsArray($user);
        $this->assertEquals('testUser', $user['name']);
    }

    public function testUpdateUser()
    {
        $this->initConfig();        
        $user = User::getUser('testUser');
        $user['displayName'] = 'Updated Test User';
        $response = User::updateUserForColumns($user, ['displayName']);
        
        $this->assertTrue($response);
    }

    public function testDeleteUser()
    {
        $this->initConfig();        
        $user = new User();
        $user->name = 'testUser';
        $response = $user->deleteUser($user);
        
        $this->assertTrue($response);
    }


    public function testGetUserByEmail()
    {
        $this->initConfig();
        $email = 'test@example.com';
        $user = User::getUserByEmail($email);
        
        $this->assertIsArray($user);
    }

    public function testCheckUserPassword()
    {
        $this->initConfig();
        $user = new User();
        $user->name = 'test_user';
        $user->password = 'test_password'; 
        $isValidPassword = $user->checkUserPassword($user);

        $this->assertTrue($isValidPassword);
    }

    public function testGetSortedUsers()
    {
        $this->initConfig();
        $sorter = 'displayName';
        $limit = 10;
        $users = User::getSortedUsers($sorter, $limit);
        
        $this->assertIsArray($users);
    }

    public function testModifyUser()
    {
        $this->initConfig();
        $user = new User();
        $user->name = 'test_user';
        
        $response = $user->addUser($user);
        $this->assertTrue($response);

        $user->displayName = 'Updated Name';
        $response = $user->updateUser($user);
        $this->assertTrue($response);

        $response = $user->deleteUser($user);
        $this->assertTrue($response);
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
        $isOnline = 1;
        $count = User::getUserCount($isOnline);
        $this->assertIsInt($count);
    }
}
