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

use Casdoor\Role;

class RoleTest extends TestBase
{
    public function testGetRoles(): void
    {
        $roles = $this->client->getRoles();
        $this->assertIsArray($roles);
    }

    public function testGetRole(): void
    {
        $role = $this->client->getRole('role-built-in');
        $this->assertIsArray($role);
    }

    public function testModifyRole(): void
    {
        $role              = new Role();
        $role->name        = 'test_role_php_sdk';
        $role->displayName = 'Test Role PHP SDK';

        $affected = $this->client->addRole($role);
        $this->assertTrue($affected);

        $role->displayName = 'Updated Test Role PHP SDK';
        $affected          = $this->client->updateRole($role);
        $this->assertTrue($affected);

        $affected = $this->client->deleteRole($role);
        $this->assertTrue($affected);
    }
}
