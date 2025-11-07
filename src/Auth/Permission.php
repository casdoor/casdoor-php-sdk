<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Permission.
 * Permission has the same definition as https://github.com/casdoor/casdoor/blob/master/object/permission.go
 *
 * @author ab1652759879@gmail.com
 */
class Permission
{
    public $owner;
    public $name;
    public $createdTime;
    public $displayName;
    public $description;
    public $users;
    public $groups;
    public $roles;
    public $domains;
    public $model;
    public $adapter;
    public $resourceType;
    public $resources;
    public $actions;
    public $effect;
    public $isEnabled;
    public $submitter;
    public $approver;
    public $approveTime;
    public $state;

    /**
     * @var AuthConfig
     */
    public static $authConfig;

    public static function initConfig(
        string $endpoint,
        string $clientId,
        string $clientSecret,
        string $certificate,
        string $organizationName,
        string $applicationName
    ): void {
        self::$authConfig = new AuthConfig(
            $endpoint,
            $clientId,
            $clientSecret,
            $certificate,
            $organizationName,
            $applicationName
        );
    }

    /**
     * Get all permissions.
     *
     * @return array
     */
    public static function getPermissions(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-permissions', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $permissions = json_decode($stream->__toString(), true);

        return $permissions;
    }

    /**
     * Get permissions by role.
     *
     * @param string $roleName
     *
     * @return array
     */
    public static function getPermissionsByRole(string $roleName): array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $roleName,
        ];

        $url = Util::getUrl('get-permissions-by-role', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $permissions = json_decode($stream->__toString(), true);

        return $permissions;
    }

    /**
     * Get a specific permission.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getPermission(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-permission', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $permission = json_decode($stream->__toString(), true);

        return $permission;
    }

    /**
     * Add a permission.
     *
     * @param Permission $permission
     *
     * @return bool
     */
    public static function addPermission(Permission $permission): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $permission->name,
        ];

        $permission->owner = self::$authConfig->organizationName;
        $postData = json_encode($permission);
        $response = Util::doPost('add-permission', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update a permission.
     *
     * @param Permission $permission
     *
     * @return bool
     */
    public static function updatePermission(Permission $permission): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $permission->name,
        ];

        $permission->owner = self::$authConfig->organizationName;
        $postData = json_encode($permission);
        $response = Util::doPost('update-permission', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete a permission.
     *
     * @param Permission $permission
     *
     * @return bool
     */
    public static function deletePermission(Permission $permission): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $permission->name,
        ];

        $permission->owner = self::$authConfig->organizationName;
        $postData = json_encode($permission);
        $response = Util::doPost('delete-permission', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
