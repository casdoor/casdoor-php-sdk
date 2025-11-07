<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Role.
 * Role has the same definition as https://github.com/casdoor/casdoor/blob/master/object/role.go#L24
 *
 * @author ab1652759879@gmail.com
 */
class Role
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
    public $isEnabled;

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
     * Get all roles.
     *
     * @return array
     */
    public static function getRoles(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-roles', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $roles = json_decode($stream->__toString(), true);

        return $roles;
    }

    /**
     * Get a specific role.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getRole(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-role', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $role = json_decode($stream->__toString(), true);

        return $role;
    }

    /**
     * Add a role.
     *
     * @param Role $role
     *
     * @return bool
     */
    public static function addRole(Role $role): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $role->name,
        ];

        $role->owner = self::$authConfig->organizationName;
        $postData = json_encode($role);
        $response = Util::doPost('add-role', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update a role.
     *
     * @param Role $role
     *
     * @return bool
     */
    public static function updateRole(Role $role): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $role->name,
        ];

        $role->owner = self::$authConfig->organizationName;
        $postData = json_encode($role);
        $response = Util::doPost('update-role', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete a role.
     *
     * @param Role $role
     *
     * @return bool
     */
    public static function deleteRole(Role $role): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $role->name,
        ];

        $role->owner = self::$authConfig->organizationName;
        $postData = json_encode($role);
        $response = Util::doPost('delete-role', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
