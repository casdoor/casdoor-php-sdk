<?php

namespace Casdoor\Tests;

use Casdoor\Auth\Application;

class ApplicationTest
{
    public function initConfig()
    {
        $endpoint = 'http://127.0.0.1:8000';
        $clientId = '6a4030b5f9fd3d00456f';
        $clientSecret = '5b17bf8c0505d94d5de7615f030bf3f8a9c0d8a0';
        $jwtSecret = file_get_contents(dirname(__FILE__) . '/public_key.pem');
        $organizationName = 'built-in';
        $applicationName = 'testApp';
        Application::initConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName, $applicationName);
    }

    /**
     * Default applicationConfig parameters
     */
    public function applicationConfig(){
        $app = new Application(User::$authConfig);
        $app->name = "default";
        $app->displayname=$app->name;
        $app->owner = "admin";
        $app->providers=[["name" => "provider_captcha_default", "canSignUp" => false, "canSignIn" => false, "canUnlink" => false, "prompted" => false, "alertType" => "None"]];
        $app->logo = "https://cdn.casbin.org/img/casdoor-logo_1185x256.png";
        $app->organization = "built-in";
        $app->cert = "cert-built-in";
        $app->redirectUris = ["http://localhost:9000/callback"];
        $app->tokenFormat = "JWT";
        $app->expireInHours = 168;
        $app->formOffset = 8;
        $app->signupItems = [
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
        $app->enablePassword = true;
        $app->enableSignUp = true;
        $app->enableSigninSession = false;
        $app->enableCodeSignin = false;
        $app->enableSamlCompress = false;
        return $app;
    }

    public function addApplication(){
        $this->initConfig();
        $app = $this->applicationConfig();
        $app->name="testApp";   # Modify config parameters
        $app->displayname="testCompanyApp";
        $res = $app->addApplication();
    }

    public function deleteApplication(){
        $this->initConfig();
        $app = $this->applicationConfig();
        $app->name="testApp";   # Modify config parameters
        $res = $app->deleteApplication();
    }
}