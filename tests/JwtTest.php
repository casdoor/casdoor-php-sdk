<?php

declare(strict_types=1);

namespace Casdoor\Tests;

use Casdoor\Auth\Jwt;
use Casdoor\Auth\AuthConfig;
use Casdoor\Auth\User;
use Casdoor\Exceptions\CasdoorException;
use PHPUnit\Framework\TestCase;

/**
 * Test case for JWT parsing functionality
 */
class JwtTest extends TestCase
{
    private AuthConfig $authConfig;
    private Jwt $jwt;

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
        
        $this->jwt = new Jwt();
    }

    /**
     * Test JWT parsing with invalid format (not 3 parts)
     */
    public function testParseJwtTokenInvalidFormat(): void
    {
        $this->expectException(CasdoorException::class);
        $this->expectExceptionMessage('The JWT string must contain two dots');
        
        $invalidToken = 'invalid.token';
        $this->jwt->parseJwtToken($invalidToken, $this->authConfig);
    }

    /**
     * Test JWT parsing with missing algorithm header
     */
    public function testParseJwtTokenMissingAlgorithm(): void
    {
        // Create a token with no 'alg' header
        $header = base64_encode(json_encode(['typ' => 'JWT']));
        $payload = base64_encode(json_encode(['sub' => '1234567890']));
        $signature = base64_encode('signature');
        
        $token = "$header.$payload.$signature";
        
        $this->expectException(CasdoorException::class);
        $this->expectExceptionMessage('Provided token is missing a alg header');
        
        $this->jwt->parseJwtToken($token, $this->authConfig);
    }

    /**
     * Test JWT parsing with invalid signature
     */
    public function testParseJwtTokenInvalidSignature(): void
    {
        // Create a token with proper structure but invalid signature
        $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode(['sub' => '1234567890', 'name' => 'Test User']));
        $signature = base64_encode('invalid_signature');
        
        $token = "$header.$payload.$signature";
        
        $this->expectException(CasdoorException::class);
        $this->expectExceptionMessage('Cannot verify signature');
        
        $this->jwt->parseJwtToken($token, $this->authConfig);
    }

    /**
     * Test JWT parsing with real token
     *
     * @group integration
     */
    public function testParseJwtTokenValid(): void
    {
        $this->markTestSkipped('Requires a valid JWT token from live Casdoor server');
        
        // This would require a real token from the Casdoor server
        $validToken = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9...';
        
        User::initConfig(
            TestUtil::TEST_ENDPOINT,
            TestUtil::TEST_CLIENT_ID,
            TestUtil::TEST_CLIENT_SECRET,
            TestUtil::TEST_JWT_PUBLIC_KEY,
            TestUtil::TEST_ORGANIZATION,
            TestUtil::TEST_APPLICATION
        );
        
        $result = $this->jwt->parseJwtToken($validToken, User::$authConfig);
        $this->assertIsArray($result);
    }

    /**
     * Test Jwt object instantiation
     */
    public function testJwtObjectCreation(): void
    {
        $jwt = new Jwt();
        $this->assertInstanceOf(Jwt::class, $jwt);
    }
}
