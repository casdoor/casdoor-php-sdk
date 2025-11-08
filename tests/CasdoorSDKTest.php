<?php

// Copyright 2023 The Casdoor Authors. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace Casdoor\Tests;

use Casdoor\CasdoorSDK;
use Casdoor\Entities\User;
use PHPUnit\Framework\TestCase;

/**
 * Test suite for Casdoor PHP SDK
 * 
 * Note: These tests require a running Casdoor instance.
 * Update the configuration below to match your setup.
 */
class CasdoorSDKTest extends TestCase
{
    private static ?CasdoorSDK $sdk = null;
    
    /**
     * Initialize SDK with test configuration
     */
    public static function setUpBeforeClass(): void
    {
        $endpoint = getenv('CASDOOR_ENDPOINT') ?: 'http://localhost:8000';
        $clientId = getenv('CASDOOR_CLIENT_ID') ?: 'c64b12723aefb65a88ce';
        $clientSecret = getenv('CASDOOR_CLIENT_SECRET') ?: 'c0c9d483a87332751b2564635765d71c9f6a2e83';
        $organizationName = getenv('CASDOOR_ORGANIZATION') ?: 'built-in';
        $applicationName = getenv('CASDOOR_APPLICATION') ?: 'app-built-in';
        
        // Load certificate
        $certPath = __DIR__ . '/public_key.pem';
        if (file_exists($certPath)) {
            $certificate = file_get_contents($certPath);
        } else {
            $certificate = '';
        }
        
        self::$sdk = new CasdoorSDK(
            $endpoint,
            $clientId,
            $clientSecret,
            $certificate,
            $organizationName,
            $applicationName
        );
    }
    
    /**
     * Test SDK initialization
     */
    public function testSdkInitialization(): void
    {
        $this->assertInstanceOf(CasdoorSDK::class, self::$sdk);
        $this->assertNotNull(self::$sdk->getClient());
    }
    
    /**
     * Test auth service
     */
    public function testAuthService(): void
    {
        $authService = self::$sdk->auth();
        $this->assertNotNull($authService);
        
        // Test sign-in URL generation
        $signinUrl = $authService->getSigninUrl('http://localhost/callback');
        $this->assertStringContainsString('/login/oauth/authorize', $signinUrl);
        $this->assertStringContainsString('client_id=', $signinUrl);
        
        // Test sign-up URL generation
        $signupUrl = $authService->getSignupUrl(true);
        $this->assertStringContainsString('/signup/', $signupUrl);
    }
    
    /**
     * Test JWT service
     */
    public function testJwtService(): void
    {
        $jwtService = self::$sdk->jwt();
        $this->assertNotNull($jwtService);
        
        // Note: Actual JWT parsing requires a valid token from Casdoor
        // This test just verifies the service is accessible
    }
    
    /**
     * Test user service - get users
     *
     * This test will be skipped if Casdoor is not accessible
     */
    public function testGetUsers(): void
    {
        $this->markTestSkipped('Requires running Casdoor instance');
        
        $users = self::$sdk->users()->getUsers();
        $this->assertIsArray($users);
    }
    
    /**
     * Test user service - get user count
     *
     * This test will be skipped if Casdoor is not accessible
     */
    public function testGetUserCount(): void
    {
        $this->markTestSkipped('Requires running Casdoor instance');
        
        $count = self::$sdk->users()->getUserCount();
        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }
    
    /**
     * Test user entity
     */
    public function testUserEntity(): void
    {
        $user = new User();
        $user->owner = 'built-in';
        $user->name = 'test-user';
        $user->displayName = 'Test User';
        $user->email = 'test@example.com';
        
        $this->assertEquals('built-in/test-user', $user->getId());
        
        // Test array conversion
        $array = $user->toArray();
        $this->assertIsArray($array);
        $this->assertEquals('test-user', $array['name']);
        
        // Test from array
        $user2 = User::fromArray($array);
        $this->assertEquals($user->name, $user2->name);
        $this->assertEquals($user->email, $user2->email);
    }
    
    /**
     * Test user CRUD operations
     *
     * This test will be skipped if Casdoor is not accessible
     */
    public function testUserCRUD(): void
    {
        $this->markTestSkipped('Requires running Casdoor instance');
        
        $userService = self::$sdk->users();
        
        // Create test user
        $user = new User();
        $user->owner = 'built-in';
        $user->name = 'test-user-' . time();
        $user->displayName = 'Test User';
        $user->email = 'test@example.com';
        
        // Add user
        $result = $userService->addUser($user);
        $this->assertTrue($result);
        
        // Get user
        $fetchedUser = $userService->getUser($user->name);
        $this->assertNotNull($fetchedUser);
        $this->assertEquals($user->name, $fetchedUser->name);
        
        // Update user
        $fetchedUser->displayName = 'Updated Test User';
        $result = $userService->updateUser($fetchedUser);
        $this->assertTrue($result);
        
        // Delete user
        $result = $userService->deleteUser($fetchedUser);
        $this->assertTrue($result);
    }
    
    /**
     * Test OAuth token retrieval
     *
     * This test will be skipped as it requires an authorization code
     */
    public function testGetOAuthToken(): void
    {
        $this->markTestSkipped('Requires valid authorization code from Casdoor');
        
        // Example code (would need to be obtained from actual OAuth flow)
        $code = 'test-authorization-code';
        $state = 'test-state';
        
        $token = self::$sdk->auth()->getOAuthToken($code, $state);
        $this->assertNotEmpty($token->accessToken);
    }
}
