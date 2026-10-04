<?php
// Copyright 2026 The Casdoor Authors. All Rights Reserved.
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

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Exceptions\CasdoorException;
use Casdoor\User;

class AuthTest extends TestBase
{
    // the CI user "admin" of the CI application's organization (built-in) has the password "123"
    private const USERNAME = 'admin';
    private const PASSWORD = '123';

    public function testOAuthTokenByPassword(): void
    {
        $token = $this->client->getOAuthTokenByPassword(self::USERNAME, self::PASSWORD);
        $this->assertNotEmpty($token->getToken());
        $this->assertNotEmpty($token->getRefreshToken());

        $refreshed = $this->client->refreshOAuthToken($token->getRefreshToken());
        $this->assertNotEmpty($refreshed->getToken());
    }

    public function testWithAccessToken(): void
    {
        $token = $this->client->getOAuthTokenByPassword(self::USERNAME, self::PASSWORD);

        $account = $this->client->withAccessToken($token->getToken())->getAccount();
        $this->assertSame(self::USERNAME, $account['name']);

        // the original client still calls the APIs as the application
        $this->assertSame('', $this->client->accessToken);

        $this->client->logoutCurrentSession($token->getToken());
        $this->client->logout($this->client->getOAuthTokenByPassword(self::USERNAME, self::PASSWORD)->getToken());

        $this->expectException(CasdoorException::class);
        $this->client->logout('');
    }

    public function testUserExtra(): void
    {
        $user              = new User();
        $user->owner       = $this->client->organizationName;
        $user->name        = 'user_' . bin2hex(random_bytes(4));
        $user->createdTime = date('c');
        $user->displayName = $user->name;
        $user->email       = $user->name . '@example.com';
        $user->phone       = '202555' . random_int(1000, 9999);
        $user->countryCode = 'US';
        $user->password    = '123456';
        $this->assertTrue($this->client->addUser($user));

        $byEmail = $this->client->getUserByEmail($user->email);
        $this->assertSame($user->name, $byEmail['name']);
        $this->assertSame($user->name, $this->client->getUserByPhone($user->phone)['name']);
        $this->assertSame($user->name, $this->client->getUserByUserId($byEmail['id'])['name']);
        $this->assertCount(1, $this->client->getSortedUsers('created_time', 1));

        $user->id          = $byEmail['id'];
        $user->displayName = 'Updated by id';
        $this->assertTrue($this->client->updateUserById($user->owner . '/' . $user->name, $user));
        $user->displayName = 'Updated by user id';
        $this->assertTrue($this->client->updateUserByUserId($user->owner, $user->id, $user));
        $this->assertSame('Updated by user id', $this->client->getUser($user->name)['displayName']);

        $this->assertTrue($this->client->checkUserPassword($user));
        $user->password = 'wrong-password';
        $this->assertFalse($this->client->checkUserPassword($user));

        $this->assertTrue($this->client->deleteUser($user));
    }

    public function testOwner(): void
    {
        $org = $this->client->organizationName;
        $this->assertSame($org . '/role', $this->client->getId('role'));
        $this->assertSame('other/role', $this->client->getId('other/role'));
        $this->assertNotEmpty($this->client->getOrganizationNames());
        $this->assertContains('app-casbin', array_column($this->client->getOrganizationApplications(), 'name'));
        $this->assertNotEmpty($this->client->getGlobalCerts());
    }
}
