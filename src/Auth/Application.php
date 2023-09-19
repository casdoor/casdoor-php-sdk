<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;
use Casdoor\Exceptions\CasdoorException;

/**
 * Class Application is used to add or delete your casdoor application
 */
class Application
{
    public string $owner;
    public string $name;
    public string $createdTime;
    public array $providers;
    public string $displayName;
    public string $logo;
    public string $homepageUrl;
    public string $description;
    public string $organization;
    public string $cert;
    public bool   $enablePassword;
    public bool   $enableSignUp;
    public bool   $enableSigninSession;
    public bool   $enableCodeSignin;
    public bool   $enableSamlCompress;

    public string $clientId;
    public string $clientSecret;
    public array  $redirectUris;
    public string $tokenFormat;
    public int    $expireInHours;
    public int    $formOffset;
    public int    $refreshExpireInHours;
    public string $signupUrl;
    public string $signinUrl;
    public string $forgetUrl;
    public string $affiliationUrl;
    public string $termsOfUse;
    public string $signupHtml;
    public string $signinHtml;
    public array $signupItems ;
    public static $authConfig;

    public static function initConfig(string $endpoint, string $clientId, string $clientSecret, string $certificate, string $organizationName, string $applicationName): void
    {
        self::$authConfig = new AuthConfig($endpoint, $clientId, $clientSecret, $certificate, $organizationName, $applicationName);
    }
    
    public function __construct(AuthConfig $authConfig)
    {
        $this->authConfig = $authConfig;
        $this->createdTime = date("y-m-d H:i:s", time());
    }

    public function addApplication(): bool
    {
        if (!isset($this->name)) {
            throw new CasdoorException("name is empty");
        }
        $postBytes = json_encode($this, JSON_THROW_ON_ERROR);
        $resp = Util::doPost('add-application', [], $this->authConfig, $postBytes, false);
        return $resp->data == 'Affected';
    }

    public function deleteApplication(): bool
    {
        if (!isset($this->name)) {
            throw new CasdoorException("name is empty");
        }
        $postBytes = json_encode($this, JSON_THROW_ON_ERROR);

        $resp = Util::doPost('delete-application', [], $this->authConfig, $postBytes, false);

        return $resp->data == 'Affected';
    }
}
