# casdoor-php-sdk

[![Latest Stable Version](http://poser.pugx.org/casdoor/casdoor-php-sdk/v)](https://packagist.org/packages/casdoor/casdoor-php-sdk) [![Total Downloads](http://poser.pugx.org/casdoor/casdoor-php-sdk/downloads)](https://packagist.org/packages/casdoor/casdoor-php-sdk) [![Latest Unstable Version](http://poser.pugx.org/casdoor/casdoor-php-sdk/v/unstable)](https://packagist.org/packages/casdoor/casdoor-php-sdk) [![License](http://poser.pugx.org/casdoor/casdoor-php-sdk/license)](https://packagist.org/packages/casdoor/casdoor-php-sdk) [![PHP Version Require](http://poser.pugx.org/casdoor/casdoor-php-sdk/require/php)](https://packagist.org/packages/casdoor/casdoor-php-sdk)

# 描述
Casdoor的PHP SDK将允许您轻松地将应用程序连接到Casdoor身份验证系统，而无需从头开始实现。

# 步骤一、Composer安装casdoor-php-sdk
在你的php应用目录下，执行以下命令：
```
composer require casdoor/casdoor-php-sdk
```
或者使用composer.json添加如下代码：
```
{
    "require": {
        "vendor/package": "*",
    }
}
```
然后执行composer install使其生效。
同时创建一个OauthTest.php文件，并引入SDK包。
```php
<?php

namespace Casdoor\Tests;

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\Jwt;
use Casdoor\Auth\Token;
use Casdoor\Auth\User;

class OauthTest extends TestCase
{
  public function xxx(){}
}
```
# 步骤二、配置参数
该步骤参考的initConfig()方法进行配置

| 参数名 | 是否必须 | 描述 |
| --- | --- | --- |
| endpoint | Yes | Casdoor的后端API地址，例如：http://localhost:8000 |
| clientId | Yes | 当前应用的客户端ID |
| clientSecret | Yes | 当前应用的客户端密钥 |
| certificate | Yes | 证书模块下的 x509格式的公钥证书（文件形式public_key.pem） |
| organizationName | Yes | 当前应用配置的组织名称 |
| applicationName | Yes | 当前应用的名称 |

参考代码：
```php
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
```
# 步骤三：获得用户JWT令牌
从登录页面进行登录后，然后页面会重定向到带有code和state的链接，如：[https://forum.casbin.com?code=xxx&state=yyyy](https://forum.casbin.com?code=xxx&state=yyyy.)
在示例文件中，填入code和state，并调用testGetOauthToken()方法，然后解析jwt令牌。
```php
public $code = "cc78dc9953ca6ae6ab58";
public $state = "casdoor";
public function testGetOauthToken()
{
    $this->initConfig();
    $token = new Token();
    $accessToken = $token->getOAuthToken($this->code, $this->state);
    $this->assertIsString($accessToken->getToken());
}
```
JWT令牌代表用户的身份，有权调用相关的API。
# 步骤四：校验与解析用户令牌
用户传入JWT令牌，testParseJwtToken函数会调用公钥对该JWT令牌进行校验。若校验通过，则返回Array对象，内含用户的账号信息。
```php
public function testParseJwtToken()
{
    $this->initConfig();
    $token = "eyJhxxxx";	// from testGetOauthToken()
    $jwt = new Jwt();
    $result = $jwt->parseJwtToken($token, User::$authConfig);
    $this->assertIsArray();
}
```
# 步骤五：更新用户信息
testModifyUser调用应用配置（非用户令牌）作为更新权限，对用户的信息做CURD操作。
```php
public function testModifyUser()
{
    $this->initConfig();
    $user = new User();

    # Delete User
    $user->name = 'user_hn99qa';
    $response = $user->deleteUser($user);
    $this->assertTrue($response);

    # Add User
    $response = $user->addUser($user);
    $this->assertTrue($response);

    # Update User
    $user->phone = 'phone';
    $user->displayName = 'display name';
    $response = $user->updateUser($user);
    $this->assertTrue($response);
}
```
# 其他：用户交互

- User::getUser() ，通过用户名来获取用户信息
- User::getUsers()，获得所有用户的信息
- User::getUserCount()，获得当前用户数量
