<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Record (Audit Record).
 *
 * @author ab1652759879@gmail.com
 */
class Record
{
    public $id;
    public $owner;
    public $name;
    public $createdTime;
    public $organization;
    public $clientIp;
    public $user;
    public $method;
    public $requestUri;
    public $action;
    public $object;
    public $extendedUser;
    public $isTriggered;

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
     * Get all records.
     *
     * @return array
     */
    public static function getRecords(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-records', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $records = json_decode($stream->__toString(), true);

        return $records;
    }

    /**
     * Get a specific record.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getRecord(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-record', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $record = json_decode($stream->__toString(), true);

        return $record;
    }
}
