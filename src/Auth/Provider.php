<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Provider.
 *
 * @author ab1652759879@gmail.com
 */
class Provider
{
    public $owner;
    public $name;
    public $createdTime;
    public $displayName;
    public $category;
    public $type;
    public $subType;
    public $method;
    public $clientId;
    public $clientSecret;
    public $clientId2;
    public $clientSecret2;
    public $cert;
    public $customAuthUrl;
    public $customTokenUrl;
    public $customUserInfoUrl;
    public $customLogo;
    public $scopes;
    public $userMapping;
    public $host;
    public $port;
    public $disableSsl;
    public $title;
    public $content;
    public $receiver;
    public $regionId;
    public $signName;
    public $templateCode;
    public $appId;
    public $endpoint;
    public $intranetEndpoint;
    public $domain;
    public $bucket;
    public $pathPrefix;
    public $metadata;
    public $idP;
    public $issuerUrl;
    public $enableSignAuthnRequest;
    public $providerUrl;

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
     * Get all providers.
     *
     * @return array
     */
    public static function getProviders(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-providers', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $providers = json_decode($stream->__toString(), true);

        return $providers;
    }

    /**
     * Get a specific provider.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getProvider(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-provider', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $provider = json_decode($stream->__toString(), true);

        return $provider;
    }

    /**
     * Add a provider.
     *
     * @param Provider $provider
     *
     * @return bool
     */
    public static function addProvider(Provider $provider): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $provider->name,
        ];

        $provider->owner = self::$authConfig->organizationName;
        $postData = json_encode($provider);
        $response = Util::doPost('add-provider', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update a provider.
     *
     * @param Provider $provider
     *
     * @return bool
     */
    public static function updateProvider(Provider $provider): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $provider->name,
        ];

        $provider->owner = self::$authConfig->organizationName;
        $postData = json_encode($provider);
        $response = Util::doPost('update-provider', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete a provider.
     *
     * @param Provider $provider
     *
     * @return bool
     */
    public static function deleteProvider(Provider $provider): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $provider->name,
        ];

        $provider->owner = self::$authConfig->organizationName;
        $postData = json_encode($provider);
        $response = Util::doPost('delete-provider', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
