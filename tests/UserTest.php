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

use Casdoor\Auth\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser(): void
    {
        User::initConfig(
            TestUtil::TEST_ENDPOINT,
            TestUtil::TEST_CLIENT_ID,
            TestUtil::TEST_CLIENT_SECRET,
            TestUtil::TEST_JWT_PUBLIC_KEY,
            TestUtil::TEST_ORGANIZATION,
            TestUtil::TEST_APPLICATION
        );

        $name = TestUtil::getRandomName('User');

        // Add a new object
        $user = new User();
        $user->owner = TestUtil::TEST_ORGANIZATION;
        $user->name = $name;
        $user->createdTime = TestUtil::getCurrentTime();
        $user->displayName = $name;
        
        $affected = User::addUser($user);
        if (!$affected) {
            $this->fail('Failed to add object');
        }

        // Get all objects, check if our added object is inside the list
        $users = User::getUsers();
        if (!is_array($users)) {
            $this->fail('Failed to get objects');
        }
        
        $found = false;
        foreach ($users as $item) {
            if (isset($item['name']) && $item['name'] === $name) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $this->fail('Added object not found in list');
        }

        // Get the object
        $user = User::getUser($name);
        if (!is_array($user)) {
            $this->fail('Failed to get object');
        }
        if ($user['name'] !== $name) {
            $this->fail(sprintf('Retrieved object does not match added object: %s != %s', $user['name'], $name));
        }

        // Update the object
        $updatedDisplayName = 'Updated Casdoor Website';
        $userObj = new User();
        $userObj->owner = TestUtil::TEST_ORGANIZATION;
        $userObj->name = $name;
        $userObj->displayName = $updatedDisplayName;
        
        $affected = User::updateUser($userObj);
        if (!$affected) {
            $this->fail('Failed to update object');
        }

        // Validate the update
        $updatedUser = User::getUser($name);
        if (!is_array($updatedUser)) {
            $this->fail('Failed to get updated object');
        }
        if ($updatedUser['displayName'] !== $updatedDisplayName) {
            $this->fail(sprintf('Failed to update object, description mismatch: %s != %s', $updatedUser['displayName'], $updatedDisplayName));
        }

        // Delete the object
        $userObj = new User();
        $userObj->owner = TestUtil::TEST_ORGANIZATION;
        $userObj->name = $name;
        
        $affected = User::deleteUser($userObj);
        if (!$affected) {
            $this->fail('Failed to delete object');
        }

        // Validate the deletion
        $deletedUser = User::getUser($name);
        if ($deletedUser !== null && !empty($deletedUser)) {
            $this->fail('Failed to delete object, it\'s still retrievable');
        }
    }
}
