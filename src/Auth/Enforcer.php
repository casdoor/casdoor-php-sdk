<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Enforcer (Policy Enforcer).
 *
 * @author ab1652759879@gmail.com
 */
class Enforcer
{
    public $owner;
    public $name;
    public $createdTime;
    public $updatedTime;
    public $displayName;
    public $description;
    public $model;
    public $adapter;
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
     * Get all enforcers.
     *
     * @return array
     */
    public static function getEnforcers(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-enforcers', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $enforcers = json_decode($stream->__toString(), true);

        return $enforcers;
    }

    /**
     * Get a specific enforcer.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getEnforcer(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-enforcer', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $enforcer = json_decode($stream->__toString(), true);

        return $enforcer;
    }

    /**
     * Add an enforcer.
     *
     * @param Enforcer $enforcer
     *
     * @return bool
     */
    public static function addEnforcer(Enforcer $enforcer): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $enforcer->name,
        ];

        $enforcer->owner = self::$authConfig->organizationName;
        $postData = json_encode($enforcer);
        $response = Util::doPost('add-enforcer', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update an enforcer.
     *
     * @param Enforcer $enforcer
     *
     * @return bool
     */
    public static function updateEnforcer(Enforcer $enforcer): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $enforcer->name,
        ];

        $enforcer->owner = self::$authConfig->organizationName;
        $postData = json_encode($enforcer);
        $response = Util::doPost('update-enforcer', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete an enforcer.
     *
     * @param Enforcer $enforcer
     *
     * @return bool
     */
    public static function deleteEnforcer(Enforcer $enforcer): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $enforcer->name,
        ];

        $enforcer->owner = self::$authConfig->organizationName;
        $postData = json_encode($enforcer);
        $response = Util::doPost('delete-enforcer', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
