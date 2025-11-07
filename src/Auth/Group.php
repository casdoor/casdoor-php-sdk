<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Group.
 *
 * @author ab1652759879@gmail.com
 */
class Group
{
    public $owner;
    public $name;
    public $createdTime;
    public $updatedTime;
    public $displayName;
    public $manager;
    public $contactEmail;
    public $type;
    public $parentId;
    public $isTopGroup;
    public $users;
    public $title;
    public $key;
    public $children;
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
     * Get all groups.
     *
     * @return array
     */
    public static function getGroups(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-groups', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $groups = json_decode($stream->__toString(), true);

        return $groups;
    }

    /**
     * Get a specific group.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getGroup(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-group', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $group = json_decode($stream->__toString(), true);

        return $group;
    }

    /**
     * Add a group.
     *
     * @param Group $group
     *
     * @return bool
     */
    public static function addGroup(Group $group): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $group->name,
        ];

        $group->owner = self::$authConfig->organizationName;
        $postData = json_encode($group);
        $response = Util::doPost('add-group', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update a group.
     *
     * @param Group $group
     *
     * @return bool
     */
    public static function updateGroup(Group $group): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $group->name,
        ];

        $group->owner = self::$authConfig->organizationName;
        $postData = json_encode($group);
        $response = Util::doPost('update-group', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete a group.
     *
     * @param Group $group
     *
     * @return bool
     */
    public static function deleteGroup(Group $group): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $group->name,
        ];

        $group->owner = self::$authConfig->organizationName;
        $postData = json_encode($group);
        $response = Util::doPost('delete-group', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
