<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\AuthConfig;
use Casdoor\Auth\Sms;

class SmsTest extends TestCase
{
    public function initConfig()
    {
        $endpoint = 'http://127.0.0.1:8000';
        $clientId = 'c64b12723aefb65a88ce';
        $clientSecret = 'c0c9d483a87332751b2564635765d71c9f6a2e83';
        $jwtSecret = file_get_contents(dirname(__FILE__) . '/public_key.pem');
        $organizationName = 'built-in';
        $applicationName = 'testApp';
        User::initConfig($endpoint, $clientId, $clientSecret, $jwtSecret, $organizationName, $applicationName);
    }

    public function testSendSms()
    {

        $this->initConfig();
        $smsHandler = new Sms( User::$authConfig, 'Content', '838625448');

        $this->expectException(\Casdoor\Exceptions\CasdoorException::class);
        $smsHandler->sendSms();

    }
}
