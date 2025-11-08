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

namespace Casdoor\Services;

use Casdoor\Client;
use Casdoor\Entities\User;

/**
 * UserService handles user-related operations
 */
class UserService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get all users
     *
     * @return User[] Array of users
     * @throws \Exception
     */
    public function getUsers(): array
    {
        $queryMap = [
            'owner' => $this->client->organizationName,
        ];

        $url = $this->client->getUrl('get-users', $queryMap);
        $bytes = $this->client->doGetBytes($url);
        $data = json_decode($bytes, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode users response: ' . json_last_error_msg());
        }

        $users = [];
        foreach ($data as $userData) {
            $users[] = User::fromArray($userData);
        }

        return $users;
    }

    /**
     * Get users with pagination
     *
     * @param int $page Page number (starting from 1)
     * @param int $pageSize Number of items per page
     * @param array<string, string> $queryMap Additional query parameters
     * @return array{users: User[], totalCount: int}
     * @throws \Exception
     */
    public function getPaginationUsers(int $page, int $pageSize, array $queryMap = []): array
    {
        $queryMap['owner'] = $this->client->organizationName;
        $queryMap['p'] = (string)$page;
        $queryMap['pageSize'] = (string)$pageSize;

        $url = $this->client->getUrl('get-users', $queryMap);
        $response = $this->client->doGetResponse($url);

        if (!is_array($response->data)) {
            throw new \Exception('Invalid response data format');
        }

        $users = [];
        foreach ($response->data as $userData) {
            $users[] = User::fromArray($userData);
        }

        $totalCount = is_numeric($response->data2) ? (int)$response->data2 : 0;

        return [
            'users' => $users,
            'totalCount' => $totalCount,
        ];
    }

    /**
     * Get sorted users
     *
     * @param string $sorter Sort field
     * @param int $limit Maximum number of users to return
     * @return User[] Array of users
     * @throws \Exception
     */
    public function getSortedUsers(string $sorter, int $limit): array
    {
        $queryMap = [
            'owner' => $this->client->organizationName,
            'sorter' => $sorter,
            'limit' => (string)$limit,
        ];

        $url = $this->client->getUrl('get-sorted-users', $queryMap);
        $bytes = $this->client->doGetBytes($url);
        $data = json_decode($bytes, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode users response: ' . json_last_error_msg());
        }

        $users = [];
        foreach ($data as $userData) {
            $users[] = User::fromArray($userData);
        }

        return $users;
    }

    /**
     * Get user count
     *
     * @param string $isOnline Filter by online status ("true", "false", or "" for all)
     * @return int User count
     * @throws \Exception
     */
    public function getUserCount(string $isOnline = ''): int
    {
        $queryMap = [
            'owner' => $this->client->organizationName,
            'isOnline' => $isOnline,
        ];

        $url = $this->client->getUrl('get-user-count', $queryMap);
        $bytes = $this->client->doGetBytes($url);
        $count = json_decode($bytes);

        if (!is_int($count)) {
            throw new \Exception('Invalid user count response');
        }

        return $count;
    }

    /**
     * Get a user by name
     *
     * @param string $name The user name
     * @return User|null The user or null if not found
     * @throws \Exception
     */
    public function getUser(string $name): ?User
    {
        $queryMap = [
            'id' => $this->client->getId($name),
        ];

        $url = $this->client->getUrl('get-user', $queryMap);
        $bytes = $this->client->doGetBytes($url);
        $data = json_decode($bytes, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode user response: ' . json_last_error_msg());
        }

        if ($data === null) {
            return null;
        }

        return User::fromArray($data);
    }

    /**
     * Get a user by email
     *
     * @param string $email The user email
     * @return User|null The user or null if not found
     * @throws \Exception
     */
    public function getUserByEmail(string $email): ?User
    {
        $queryMap = [
            'owner' => $this->client->organizationName,
            'email' => $email,
        ];

        $url = $this->client->getUrl('get-user', $queryMap);
        $bytes = $this->client->doGetBytes($url);
        $data = json_decode($bytes, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode user response: ' . json_last_error_msg());
        }

        if ($data === null) {
            return null;
        }

        return User::fromArray($data);
    }

    /**
     * Get a user by phone
     *
     * @param string $phone The user phone
     * @return User|null The user or null if not found
     * @throws \Exception
     */
    public function getUserByPhone(string $phone): ?User
    {
        $queryMap = [
            'owner' => $this->client->organizationName,
            'phone' => $phone,
        ];

        $url = $this->client->getUrl('get-user', $queryMap);
        $bytes = $this->client->doGetBytes($url);
        $data = json_decode($bytes, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode user response: ' . json_last_error_msg());
        }

        if ($data === null) {
            return null;
        }

        return User::fromArray($data);
    }

    /**
     * Get a user by user ID
     *
     * @param string $userId The user ID
     * @return User|null The user or null if not found
     * @throws \Exception
     */
    public function getUserByUserId(string $userId): ?User
    {
        $queryMap = [
            'owner' => $this->client->organizationName,
            'userId' => $userId,
        ];

        $url = $this->client->getUrl('get-user', $queryMap);
        $bytes = $this->client->doGetBytes($url);
        $data = json_decode($bytes, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode user response: ' . json_last_error_msg());
        }

        if ($data === null) {
            return null;
        }

        return User::fromArray($data);
    }

    /**
     * Get global users (admin only)
     *
     * @return User[] Array of users
     * @throws \Exception
     */
    public function getGlobalUsers(): array
    {
        $url = $this->client->getUrl('get-global-users', []);
        $bytes = $this->client->doGetBytes($url);
        $data = json_decode($bytes, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode users response: ' . json_last_error_msg());
        }

        $users = [];
        foreach ($data as $userData) {
            $users[] = User::fromArray($userData);
        }

        return $users;
    }

    /**
     * Add a new user
     *
     * @param User $user The user to add
     * @return bool True if successful
     * @throws \Exception
     */
    public function addUser(User $user): bool
    {
        [$response, $affected] = $this->client->modifyResource('add-user', $user, null);
        return $affected;
    }

    /**
     * Update an existing user
     *
     * @param User $user The user to update
     * @return bool True if successful
     * @throws \Exception
     */
    public function updateUser(User $user): bool
    {
        [$response, $affected] = $this->client->modifyResource('update-user', $user, null);
        return $affected;
    }

    /**
     * Update user with specific columns
     *
     * @param User $user The user to update
     * @param string[] $columns Columns to update
     * @return bool True if successful
     * @throws \Exception
     */
    public function updateUserForColumns(User $user, array $columns): bool
    {
        [$response, $affected] = $this->client->modifyResource('update-user', $user, $columns);
        return $affected;
    }

    /**
     * Delete a user
     *
     * @param User $user The user to delete
     * @return bool True if successful
     * @throws \Exception
     */
    public function deleteUser(User $user): bool
    {
        [$response, $affected] = $this->client->modifyResource('delete-user', $user, null);
        return $affected;
    }

    /**
     * Set user password
     *
     * @param string $owner User owner
     * @param string $name User name
     * @param string $oldPassword Old password (can be empty)
     * @param string $newPassword New password
     * @return bool True if successful
     * @throws \Exception
     */
    public function setPassword(string $owner, string $name, string $oldPassword, string $newPassword): bool
    {
        $params = [
            'userOwner' => $owner,
            'userName' => $name,
            'oldPassword' => $oldPassword,
            'newPassword' => $newPassword,
        ];

        $response = $this->client->doPost('set-password', [], json_encode($params), true, false);
        return $response->status === 'ok';
    }

    /**
     * Check user password
     *
     * @param User $user The user with password to check
     * @return bool True if password is correct
     * @throws \Exception
     */
    public function checkUserPassword(User $user): bool
    {
        [$response, $affected] = $this->client->modifyResource('check-user-password', $user, null);
        return $affected;
    }
}
