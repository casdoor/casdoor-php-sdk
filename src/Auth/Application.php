<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Application is used to add or delete your casdoor application
 */
class Application
{
    /**
     * @var string
     */
    public $owner;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $createdTime;

    /**
     * @var string
     */
    public $displayName;

    /**
     * @var string
     */
    public $logo;

    /**
     * @var string
     */
    public $homepageUrl;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $organization;

    /**
     * @var string
     */
    public $cert;

    /**
     * @var bool
     */
    public $enablePassword;

    /**
     * @var bool
     */
    public $enableSignUp;

    /**
     * @var bool
     */
    public $enableSigninSession;

    /**
     * @var bool
     */
    public $enableCodeSignin;


    /**
     * @var string
     */
    public $clientId;

    /**
     * @var string
     */
    public $clientSecret;

    /**
     * @var array
     */
    public $redirectUris;

    /**
     * @var string
     */
    public $tokenFormat;

    /**
     * @var int
     */
    public $expireInHours;

    /**
     * @var int
     */
    public $refreshExpireInHours;

    /**
     * @var string
     */
    public $signupUrl;

    /**
     * @var string
     */
    public $signinUrl;

    /**
     * @var string
     */
    public $forgetUrl;

    /**
     * @var string
     */
    public $affiliationUrl;

    /**
     * @var string
     */
    public $termsOfUse;

    /**
     * @var string
     */
    public $signupHtml;

    /**
     * @var string
     */
    public $signinHtml;

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
