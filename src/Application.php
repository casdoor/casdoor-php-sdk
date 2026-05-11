<?php

// Copyright 2024 The Casdoor Authors. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

declare(strict_types=1);

namespace Casdoor;

// Application has the same definition as https://github.com/casdoor/casdoor/blob/master/object/application.go
class Application
{
    public string $owner       = '';
    public string $name        = '';
    public string $createdTime = '';

    public string $displayName                  = '';
    public string $logo                         = '';
    public string $title                        = '';
    public string $favicon                      = '';
    public int    $order                        = 0;
    public string $homepageUrl                  = '';
    public string $description                  = '';
    public string $organization                 = '';
    public string $cert                         = '';
    public string $defaultGroup                 = '';
    public string $headerHtml                   = '';
    public bool   $enablePassword               = false;
    public bool   $enableSignUp                 = false;
    public bool   $disableSignin                = false;
    public bool   $enableSigninSession          = false;
    public bool   $enableAutoSignin             = false;
    public bool   $enableCodeSignin             = false;
    public bool   $enableExclusiveSignin        = false;
    public bool   $enableSamlCompress           = false;
    public bool   $enableSamlC14n10             = false;
    public bool   $enableSamlPostBinding        = false;
    public bool   $disableSamlAttributes        = false;
    public bool   $enableSamlAssertionSignature = false;
    public bool   $useEmailAsSamlNameId         = false;
    public bool   $enableWebAuthn               = false;
    public bool   $enableLinkWithEmail          = false;
    public string $orgChoiceMode                = '';
    public string $samlReplyUrl                 = '';
    public array  $providers                    = [];
    public array  $signinMethods                = [];
    public array  $signupItems                  = [];
    public array  $signinItems                  = [];
    public array  $grantTypes                   = [];
    public ?array $organizationObj              = null;
    public string $certPublicKey                = '';
    public array  $tags                         = [];
    public array  $samlAttributes               = [];
    public string $samlHashAlgorithm            = '';
    public bool   $isShared                     = false;
    public string $ipRestriction                = '';

    public string $clientId                = '';
    public string $clientSecret            = '';
    public array  $redirectUris            = [];
    public string $forcedRedirectOrigin    = '';
    public string $tokenFormat             = '';
    public string $tokenSigningMethod      = '';
    public array  $tokenFields             = [];
    public array  $tokenAttributes         = [];
    public float  $expireInHours           = 0.0;
    public float  $refreshExpireInHours    = 0.0;
    public int    $cookieExpireInHours     = 0;
    public string $signupUrl               = '';
    public string $signinUrl               = '';
    public string $forgetUrl               = '';
    public string $affiliationUrl          = '';
    public string $ipWhitelist             = '';
    public string $termsOfUse              = '';
    public string $signupHtml              = '';
    public string $signinHtml              = '';
    public ?array $themeData               = null;
    public string $footerHtml              = '';
    public string $formCss                 = '';
    public string $formCssMobile           = '';
    public int    $formOffset              = 0;
    public string $formSideHtml            = '';
    public string $formBackgroundUrl       = '';
    public string $formBackgroundUrlMobile = '';

    public int   $failedSigninLimit      = 0;
    public int   $failedSigninFrozenTime = 0;
    public int   $codeResendTimeout      = 0;

    public ?array $certObj = null;
}

trait ApplicationTrait
{
    public function getApplications(): array
    {
        $url = $this->getUrl('get-applications', ['owner' => 'admin']);
        return $this->doGetBytes($url);
    }

    public function getOrganizationApplications(): array
    {
        $url = $this->getUrl('get-organization-applications', [
            'owner'        => 'admin',
            'organization' => $this->organizationName,
        ]);
        return $this->doGetBytes($url);
    }

    public function getApplication(string $name): ?array
    {
        $url = $this->getUrl('get-application', ['id' => 'admin/' . $name]);
        return $this->doGetBytes($url);
    }

    public function addApplication(Application $application): bool
    {
        return $this->modifyApplication('add-application', $application);
    }

    public function updateApplication(Application $application): bool
    {
        return $this->modifyApplication('update-application', $application);
    }

    public function deleteApplication(Application $application): bool
    {
        return $this->modifyApplication('delete-application', $application);
    }

    private function modifyApplication(string $action, Application $application): bool
    {
        if ($application->owner === '') {
            $application->owner = 'admin';
        }
        $queryMap = ['id' => $application->owner . '/' . $application->name];
        $postData = json_encode($application, JSON_THROW_ON_ERROR);
        $response = $this->doPost($action, $queryMap, $postData);
        return $this->boolFromResponse($response);
    }
}
