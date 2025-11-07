<?php

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Auth\User;
use PHPUnit\Framework\TestCase;

/**
 * Test case for User class
 */
class UserTest extends TestCase
{
    protected function setUp(): void
    {
        User::initConfig(
            TestUtil::TEST_ENDPOINT,
            TestUtil::TEST_CLIENT_ID,
            TestUtil::TEST_CLIENT_SECRET,
            TestUtil::TEST_JWT_PUBLIC_KEY,
            TestUtil::TEST_ORGANIZATION,
            TestUtil::TEST_APPLICATION
        );
    }

    /**
     * Test getting all users
     *
     * @group integration
     */
    public function testGetUsers(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $users = User::getUsers();
        $this->assertIsArray($users);
    }

    /**
     * Test getting user count
     *
     * @group integration
     */
    public function testGetUserCount(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $count = User::getUserCount('true');
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    /**
     * Test getting a specific user
     *
     * @group integration
     */
    public function testGetUser(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $name = 'admin';
        $user = User::getUser($name);
        $this->assertIsArray($user);
    }

    /**
     * Test CRUD operations on user
     *
     * @group integration
     */
    public function testUserCRUD(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $name = TestUtil::getRandomName('User');
        
        // Create a new user
        $user = new User();
        $user->owner = TestUtil::TEST_ORGANIZATION;
        $user->name = $name;
        $user->createdTime = TestUtil::getCurrentTime();
        $user->displayName = $name;
        
        // Add user
        $result = $user->addUser($user);
        $this->assertTrue($result);
        
        // Get the user
        $fetchedUser = User::getUser($name);
        $this->assertIsArray($fetchedUser);
        $this->assertEquals($name, $fetchedUser['name']);
        
        // Update the user
        $user->displayName = 'Updated ' . $name;
        $user->phone = '+1234567890';
        $result = $user->updateUser($user);
        $this->assertTrue($result);
        
        // Verify update
        $updatedUser = User::getUser($name);
        $this->assertIsArray($updatedUser);
        $this->assertEquals('Updated ' . $name, $updatedUser['displayName']);
        
        // Delete the user
        $result = $user->deleteUser($user);
        $this->assertTrue($result);
        
        // Verify deletion
        $deletedUser = User::getUser($name);
        $this->assertNull($deletedUser);
    }

    /**
     * Test User object instantiation
     */
    public function testUserObjectCreation(): void
    {
        $user = new User();
        $user->name = 'testuser';
        $user->displayName = 'Test User';
        $user->email = 'test@example.com';
        
        $this->assertEquals('testuser', $user->name);
        $this->assertEquals('Test User', $user->displayName);
        $this->assertEquals('test@example.com', $user->email);
    }

    /**
     * Test User static auth config initialization
     */
    public function testInitConfig(): void
    {
        User::initConfig(
            'http://localhost:8000',
            'test_client_id',
            'test_client_secret',
            'test_certificate',
            'test_org',
            'test_app'
        );
        
        $this->assertNotNull(User::$authConfig);
        $this->assertEquals('http://localhost:8000', User::$authConfig->endpoint);
        $this->assertEquals('test_client_id', User::$authConfig->clientId);
        $this->assertEquals('test_client_secret', User::$authConfig->clientSecret);
    }
}
