<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Adapter (Policy Adapter).
 *
 * @author ab1652759879@gmail.com
 */
class Adapter
{
    public $owner;
    public $name;
    public $createdTime;
    public $useSameDb;
    public $type;
    public $databaseType;
    public $host;
    public $port;
    public $user;
    public $password;
    public $database;
    public $table;
    public $tableNamePrefix;
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
     * Get all adapters.
     *
     * @return array
     */
    public static function getAdapters(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-adapters', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $adapters = json_decode($stream->__toString(), true);

        return $adapters;
    }

    /**
     * Get a specific adapter.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getAdapter(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-adapter', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $adapter = json_decode($stream->__toString(), true);

        return $adapter;
    }

    /**
     * Add an adapter.
     *
     * @param Adapter $adapter
     *
     * @return bool
     */
    public static function addAdapter(Adapter $adapter): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $adapter->name,
        ];

        $adapter->owner = self::$authConfig->organizationName;
        $postData = json_encode($adapter);
        $response = Util::doPost('add-adapter', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update an adapter.
     *
     * @param Adapter $adapter
     *
     * @return bool
     */
    public static function updateAdapter(Adapter $adapter): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $adapter->name,
        ];

        $adapter->owner = self::$authConfig->organizationName;
        $postData = json_encode($adapter);
        $response = Util::doPost('update-adapter', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete an adapter.
     *
     * @param Adapter $adapter
     *
     * @return bool
     */
    public static function deleteAdapter(Adapter $adapter): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $adapter->name,
        ];

        $adapter->owner = self::$authConfig->organizationName;
        $postData = json_encode($adapter);
        $response = Util::doPost('delete-adapter', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
