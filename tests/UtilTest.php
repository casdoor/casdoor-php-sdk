<?php

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Auth\AuthConfig;
use Casdoor\Util\Util;
use PHPUnit\Framework\TestCase;

/**
 * Test case for Util class
 */
class UtilTest extends TestCase
{
    private AuthConfig $authConfig;

    protected function setUp(): void
    {
        $this->authConfig = new AuthConfig(
            TestUtil::TEST_ENDPOINT,
            TestUtil::TEST_CLIENT_ID,
            TestUtil::TEST_CLIENT_SECRET,
            TestUtil::TEST_JWT_PUBLIC_KEY,
            TestUtil::TEST_ORGANIZATION,
            TestUtil::TEST_APPLICATION
        );
    }

    /**
     * Test getUrl method
     */
    public function testGetUrl(): void
    {
        $action = 'get-users';
        $queryMap = [
            'owner' => 'admin',
            'pageSize' => '10'
        ];
        
        $url = Util::getUrl($action, $queryMap, $this->authConfig);
        
        $this->assertStringContainsString(TestUtil::TEST_ENDPOINT, $url);
        $this->assertStringContainsString('/api/get-users', $url);
        $this->assertStringContainsString('owner=admin', $url);
        $this->assertStringContainsString('pageSize=10', $url);
    }

    /**
     * Test getUrl method with empty query map
     */
    public function testGetUrlWithEmptyQuery(): void
    {
        $action = 'get-users';
        $queryMap = [];
        
        $url = Util::getUrl($action, $queryMap, $this->authConfig);
        
        $this->assertStringContainsString(TestUtil::TEST_ENDPOINT, $url);
        $this->assertStringContainsString('/api/get-users', $url);
        $this->assertStringEndsWith('?', $url);
    }

    /**
     * Test createForm method
     */
    public function testCreateForm(): void
    {
        $formData = json_encode([
            'field1' => 'value1',
            'field2' => 'value2',
            'field3' => 'value3'
        ]);
        
        $result = Util::createForm($formData);
        
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        
        $this->assertEquals('field1', $result[0]['name']);
        $this->assertEquals('value1', $result[0]['contents']);
        $this->assertEquals('file', $result[0]['filename']);
        
        $this->assertEquals('field2', $result[1]['name']);
        $this->assertEquals('value2', $result[1]['contents']);
    }

    /**
     * Test createForm with empty data
     */
    public function testCreateFormWithEmptyData(): void
    {
        $formData = json_encode([]);
        
        $result = Util::createForm($formData);
        
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /**
     * Test doPost method
     *
     * @group integration
     */
    public function testDoPost(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $action = 'get-users';
        $queryMap = ['owner' => TestUtil::TEST_ORGANIZATION];
        $postData = json_encode(['test' => 'data']);
        
        $response = Util::doPost($action, $queryMap, $this->authConfig, $postData, false);
        
        $this->assertIsObject($response);
    }

    /**
     * Test doGetStream method
     *
     * @group integration
     */
    public function testDoGetStream(): void
    {
        $this->markTestSkipped('Requires live Casdoor server connection');
        
        $url = TestUtil::TEST_ENDPOINT . '/api/get-users?owner=' . TestUtil::TEST_ORGANIZATION;
        
        $stream = Util::doGetStream($url, $this->authConfig);
        
        $this->assertNotNull($stream);
    }

    /**
     * Test getUrl generates proper query string format
     */
    public function testGetUrlQueryStringFormat(): void
    {
        $action = 'test-action';
        $queryMap = [
            'param1' => 'value1',
            'param2' => 'value2'
        ];
        
        $url = Util::getUrl($action, $queryMap, $this->authConfig);
        
        // Verify URL structure
        $this->assertMatchesRegularExpression('/\/api\/test-action\?param1=value1&param2=value2/', $url);
    }
}
