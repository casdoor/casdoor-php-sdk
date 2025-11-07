<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Cert (Certificate).
 *
 * @author ab1652759879@gmail.com
 */
class Cert
{
    public $owner;
    public $name;
    public $createdTime;
    public $displayName;
    public $scope;
    public $type;
    public $cryptoAlgorithm;
    public $bitSize;
    public $expireInYears;
    public $certificate;
    public $privateKey;
    public $authorityPublicKey;
    public $authorityRootPublicKey;

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
     * Get all certificates.
     *
     * @return array
     */
    public static function getCerts(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-certs', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $certs = json_decode($stream->__toString(), true);

        return $certs;
    }

    /**
     * Get global certificates.
     *
     * @return array
     */
    public static function getGlobalCerts(): array
    {
        $url = Util::getUrl('get-global-certs', [], self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $certs = json_decode($stream->__toString(), true);

        return $certs;
    }

    /**
     * Get a specific certificate.
     *
     * @param string $name
     *
     * @return array|null
     */
    public static function getCert(string $name): ?array
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $name,
        ];

        $url = Util::getUrl('get-cert', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $cert = json_decode($stream->__toString(), true);

        return $cert;
    }

    /**
     * Add a certificate.
     *
     * @param Cert $cert
     *
     * @return bool
     */
    public static function addCert(Cert $cert): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $cert->name,
        ];

        $cert->owner = self::$authConfig->organizationName;
        $postData = json_encode($cert);
        $response = Util::doPost('add-cert', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update a certificate.
     *
     * @param Cert $cert
     *
     * @return bool
     */
    public static function updateCert(Cert $cert): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $cert->name,
        ];

        $cert->owner = self::$authConfig->organizationName;
        $postData = json_encode($cert);
        $response = Util::doPost('update-cert', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete a certificate.
     *
     * @param Cert $cert
     *
     * @return bool
     */
    public static function deleteCert(Cert $cert): bool
    {
        $queryMap = [
            'id' => self::$authConfig->organizationName . '/' . $cert->name,
        ];

        $cert->owner = self::$authConfig->organizationName;
        $postData = json_encode($cert);
        $response = Util::doPost('delete-cert', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
