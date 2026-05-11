<?php

// Copyright 2024 The Casdoor Authors. All Rights Reserved.
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

use Casdoor\User;

class UserTest extends TestBase
{
    public function testGetUsers(): void
    {
        $users = $this->client->getUsers();
        $this->assertIsArray($users);
    }

    public function testGetUser(): void
    {
        $user = $this->client->getUser('admin');
        $this->assertIsArray($user);
    }

    public function testGetUserCount(): void
    {
        $count = $this->client->getUserCount('');
        $this->assertIsInt($count);
    }

    public function testModifyUser(): void
    {
        $user       = new User();
        $user->name = 'test_user_php_sdk';

        $affected = $this->client->addUser($user);
        $this->assertTrue($affected);

        $user->displayName = 'Test User PHP SDK';
        $affected          = $this->client->updateUser($user);
        $this->assertTrue($affected);

        $affected = $this->client->deleteUser($user);
        $this->assertTrue($affected);
    }

    public function testGetPaginationUsers(): void
    {
        [$users, $total] = $this->client->getPaginationUsers(1, 10);
        $this->assertIsArray($users);
        $this->assertIsInt($total);
    }
}
