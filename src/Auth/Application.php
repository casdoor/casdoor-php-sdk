<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

class Application
{
    public string $owner;
    public string $name;
    public string $createdTime;

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

    public string $clientId;
    public string $clientSecret;
    public array  $redirectUris;
    public string $tokenFormat;
    public int    $expireInHours;
    public int    $refreshExpireInHours;
    public string $signupUrl;
    public string $signinUrl;
    public string $forgetUrl;
    public string $affiliationUrl;
    public string $termsOfUse;
    public string $signupHtml;
    public string $signinHtml;

    public function __construct(string $owner, string $name)
    {
        $this->owner = $owner;
        $this->name  = $name;
    }

    public function addApplication(Application $application, AuthConfig $authConfig): bool
    {
        if ($application->owner == '') {
            $application->owner = 'admin';
        }
        $postBytes = json_encode($application, JSON_THROW_ON_ERROR);
    
        $resp = Util::doPost('add-application', [], $authConfig, $postBytes, false);
    
        return $resp->data == 'Affected';
    }
    
    public function deleteApplication(string $name, AuthConfig $authConfig): bool
    {
        $application = new Application('admin', $name);
        $postBytes = json_encode($application, JSON_THROW_ON_ERROR);
    
        $resp = Util::doPost('delete-application', [], $authConfig, $postBytes, false);

        return $resp->data == 'Affected';
    }
}
