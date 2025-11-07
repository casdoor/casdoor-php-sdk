<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Webhook.
 *
 * @author ab1652759879@gmail.com
 */
class Webhook
{
    public $owner;
    public $name;
    public $createdTime;
    public $organization;
    public $type;
    public $host;
    public $port;
    public $user;
    public $password;
    public $databaseType;
    public $database;
    public $table;
    public $tablePrimaryKey;
    public $tableColumns;
    public $affiliationTable;
    public $avatarBaseUrl;
    public $errorText;
    public $syncInterval;
    public $isReadOnly;
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
     * Get all webhooks.
     *
     * @return array
     */
    public static function getWebhooks(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-webhooks', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $webhooks = json_decode($stream->__toString(), true);

        return $webhooks;
    }

    /**
     * Get a specific webhook.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getWebhook(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-webhook', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $webhook = json_decode($stream->__toString(), true);

        return $webhook;
    }

    /**
     * Add a webhook.
     *
     * @param Webhook $webhook
     *
     * @return bool
     */
    public static function addWebhook(Webhook $webhook): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $webhook->name,
        ];

        $webhook->owner = self::$authConfig->organizationName;
        $postData = json_encode($webhook);
        $response = Util::doPost('add-webhook', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update a webhook.
     *
     * @param Webhook $webhook
     *
     * @return bool
     */
    public static function updateWebhook(Webhook $webhook): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $webhook->name,
        ];

        $webhook->owner = self::$authConfig->organizationName;
        $postData = json_encode($webhook);
        $response = Util::doPost('update-webhook', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete a webhook.
     *
     * @param Webhook $webhook
     *
     * @return bool
     */
    public static function deleteWebhook(Webhook $webhook): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $webhook->name,
        ];

        $webhook->owner = self::$authConfig->organizationName;
        $postData = json_encode($webhook);
        $response = Util::doPost('delete-webhook', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
