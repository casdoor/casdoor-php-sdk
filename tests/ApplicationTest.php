<?php

namespace Casdoor\Tests;

use Casdoor\Auth\Application;

class ApplicationTest
{
    public function initConfig()
    {
        $endpoint = 'http://127.0.0.1:8000';
        $clientId = 'c64b12723aefb65a88ce';
        $clientSecret = 'c0c9d483a87332751b2564635765d71c9f6a2e83';
        $jwtSecret = file_get_contents(dirname(__FILE__) . '/public_key.pem');
        $organizationName = 'built-in';
        $applicationName = 'testApp';
        Application::initConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName, $applicationName);
    }

    public function addApplication(){
        $this->initConfig();
        $app = new Application(User::$authConfig);
        $app->name = "test01";
        $app->displayname="test01";
        $res = $app->addApplication();
    }

    public function deleteApplication(){
        $this->initConfig();
        $app = new Application(User::$authConfig);
        $app->name = "test01";
        $res = $app->deleteApplication();
    }
}