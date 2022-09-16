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
    public string $owner = "admin";
    public string $name;
    public string $createdTime;
    public array $providers = [["name" => "provider_captcha_default", "canSignUp" => false, "canSignIn" => false, "canUnlink" => false, "prompted" => false, "alertType" => "None"]];
    public string $displayName;
    public string $logo = "https://cdn.casbin.org/img/casdoor-logo_1185x256.png";
    public string $homepageUrl;
    public string $description;
    public string $organization = "built-in";
    public string $cert = "cert-built-in";
    public bool   $enablePassword = true;
    public bool   $enableSignUp = true;
    public bool   $enableSigninSession = false;
    public bool   $enableCodeSignin = false;
    public bool   $enableSamlCompress = false;

    public string $clientId;
    public string $clientSecret;
    public array  $redirectUris = ["http://localhost:9000/callback"];
    public string $tokenFormat = "JWT";
    public int    $expireInHours = 168;
    public int    $formOffset = 8;
    public int    $refreshExpireInHours;
    public string $signupUrl;
    public string $signinUrl;
    public string $forgetUrl;
    public string $affiliationUrl;
    public string $termsOfUse;
    public string $signupHtml;
    public string $signinHtml;
    public array $signupItems = [
        [
            "name" => "ID",
            "visible" => false,
            "required" => true,
            "rule" => "Random"
        ],
        [
            "name" => "Username",
            "visible" => true,
            "required" => true,
            "rule" => "None"
        ],
        [
            "name" => "Display name",
            "visible" => true,
            "required" => true,
            "rule" => "None"
        ],
        [
            "name" => "Password",
            "visible" => true,
            "required" => true,
            "rule" => "None"
        ],
        [
            "name" => "Confirm password",
            "visible" => true,
            "required" => true,
            "rule" => "None"
        ],
        [
            "name" => "Email",
            "visible" => true,
            "required" => true,
            "rule" => "Normal"
        ],
        [
            "name" => "Phone",
            "visible" => true,
            "required" => true,
            "rule" => "None"
        ],
        [
            "name" => "Agreement",
            "visible" => true,
            "required" => true,
            "rule" => "None"
        ]
    ];
    public static $authConfig;

    public static function initConfig(string $endpoint, string $clientId, string $clientSecret, string $jwtSecret, string $organizationName, string $applicationName): void
    {
        self::$authConfig = new AuthConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName, $applicationName);
    }
    
    public function __construct(AuthConfig $authConfig)
    {
        $this->authConfig = $authConfig;
        $this->createdTime = date("y-m-d H:i:s", time());
    }

    public function addApplication(Application $Application): bool
    {
        if (!isset($this->name)) {
            throw new CasdoorException("name is empty");
        }
        $postBytes = json_encode($Application, JSON_THROW_ON_ERROR);
        $resp = Util::doPost('add-application', [], $this->authConfig, $postBytes, false);
        return $resp->data == 'Affected';
    }

    public function deleteApplication(Application $Application): bool
    {
        if (!isset($this->name)) {
            throw new CasdoorException("name is empty");
        }
        $postBytes = json_encode($Application, JSON_THROW_ON_ERROR);

        $resp = Util::doPost('delete-application', [], $this->authConfig, $postBytes, false);

        return $resp->data == 'Affected';
    }
}
