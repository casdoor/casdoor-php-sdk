<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\AuthConfig;
use Casdoor\Auth\Resource;

class ResourceTest extends TestCase
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


    public function testUploadResource()
    {

        $this->initConfig();
        
        $resourceHandler = new Resource('test_owner', 'test_resource',  User::$authConfig);

        // 准备测试数据
        $tag = 'test_tag';
        $parent = 'test_parent';
        $fullFilePath = 'test_file_path';
        $fileBytes = ['test_file_bytes'];

        
        $result = $resourceHandler->uploadResource($tag, $parent, $fullFilePath, $fileBytes);

        
        $this->assertIsArray($result);
    }
    
    public function testUploadResourceEx()
    {

        $this->initConfig();
        $resourceHandler = new Resource('test_owner', 'test_resource',  User::$authConfig);

        $user = 'test_user';
        $tag = 'test_tag';
        $parent = 'test_parent';
        $fullFilePath = 'test_file_path';
        $fileBytes = ['test_file_bytes'];
        $createdTime = '2024-03-16';
        $description = 'Test description';

        $result = $resourceHandler->uploadResourceEx($user, $tag, $parent, $fullFilePath, $fileBytes, $createdTime, $description);


        $this->assertIsArray($result);
    }

    public function testDeleteResource()
    {

        $this->initConfig();
        $resourceHandler = new Resource('test_owner', 'test_resource',  User::$authConfig);


        $result = $resourceHandler->deleteResource();

        $this->assertIsBool($result);
    }
}
