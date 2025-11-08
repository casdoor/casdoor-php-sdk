# Casdoor PHP SDK

<p align="center">
  <a href="#badge">
    <img alt="semantic-release" src="https://img.shields.io/badge/%20%20%F0%9F%93%A6%F0%9F%9A%80-semantic--release-e10079.svg">
  </a>
  <a href="https://packagist.org/packages/casdoor/casdoor-php-sdk">
    <img alt="Latest Stable Version" src="http://poser.pugx.org/casdoor/casdoor-php-sdk/v">
  </a>
  <a href="https://packagist.org/packages/casdoor/casdoor-php-sdk">
    <img alt="Total Downloads" src="http://poser.pugx.org/casdoor/casdoor-php-sdk/downloads">
  </a>
  <a href="https://packagist.org/packages/casdoor/casdoor-php-sdk">
    <img alt="License" src="http://poser.pugx.org/casdoor/casdoor-php-sdk/license">
  </a>
  <a href="https://packagist.org/packages/casdoor/casdoor-php-sdk">
    <img alt="PHP Version Require" src="http://poser.pugx.org/casdoor/casdoor-php-sdk/require/php">
  </a>
</p>

<p align="center">
  <a href="https://discord.gg/5rPsrAzK7S">
    <img alt="Discord" src="https://img.shields.io/discord/1022748306096537660?style=flat-square&logo=discord&label=discord&color=5865F2">
  </a>
</p>

Casdoor PHP SDK is the official PHP client library for [Casdoor](https://casdoor.org/), which allows you to easily integrate Casdoor authentication and authorization into your PHP applications. This SDK provides a comprehensive set of APIs to interact with Casdoor server, enabling you to manage users, organizations, applications, roles, permissions, and much more.

## 📋 Table of Contents

- [Features](#-features)
- [Installation](#-installation)
- [Quick Start](#-quick-start)
- [Configuration](#-configuration)
- [Authentication](#-authentication)
- [User Management](#-user-management)
- [Examples](#-examples)
- [Documentation](#-documentation)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

- **OAuth 2.0 Authentication**: Complete OAuth 2.0 flow implementation with token refresh
- **JWT Token Parsing**: Parse and validate JWT tokens with support for multiple algorithms (RS256, RS512, ES256, ES512)
- **User Management**: Create, read, update, and delete users with comprehensive profile support
- **Flexible Configuration**: Support for both client-specific and global configuration
- **Type Safety**: Comprehensive entity classes for type-safe operations
- **PSR-4 Compliant**: Follows PHP standards and best practices
- **Modern PHP**: Requires PHP 7.4+ with full type declarations

## 📦 Installation

To install the Casdoor PHP SDK, you need PHP 7.4 or higher and Composer. Run the following command in your PHP project:

```bash
composer require casdoor/casdoor-php-sdk
```

## 🚀 Quick Start

Here's a minimal example to get you started with Casdoor PHP SDK:

```php
<?php

require_once 'vendor/autoload.php';

use Casdoor\CasdoorSDK;

// Initialize the SDK with your Casdoor instance configuration
$sdk = new CasdoorSDK(
    'http://localhost:8000',              // endpoint
    'CLIENT_ID',                           // clientId
    'CLIENT_SECRET',                       // clientSecret
    file_get_contents('path/to/cert.pem'), // certificate (x509 format)
    'my-organization',                     // organizationName
    'my-application'                       // applicationName
);

// Get sign-in URL
$signinUrl = $sdk->auth()->getSigninUrl('http://localhost/callback');
echo "Sign in URL: $signinUrl\n";

// After OAuth callback, exchange code for token
$code = $_GET['code']; // From OAuth callback
$state = $_GET['state'];
$token = $sdk->auth()->getOAuthToken($code, $state);

// Parse JWT token
$claims = $sdk->jwt()->parseJwtToken($token->accessToken);
echo "User: " . $claims->name . "\n";

// Get user information
$user = $sdk->users()->getUser($claims->name);
echo "Email: " . $user->email . "\n";
```

## ⚙️ Configuration

### Required Parameters

| Parameter         | Required | Description                                                                                          |
|-------------------|----------|------------------------------------------------------------------------------------------------------|
| endpoint          | Yes      | The backend API address of Casdoor, for example: `http://localhost:8000`                            |
| clientId          | Yes      | The client ID of the current application                                                             |
| clientSecret      | Yes      | The client secret of the current application                                                         |
| certificate       | Yes      | Public key certificate in x509 format (content of `public_key.pem` file)                             |
| organizationName  | Yes      | The organization name of the current application configuration                                       |
| applicationName   | Yes      | The name of the current application                                                                  |

### Example Configuration

```php
<?php

use Casdoor\CasdoorSDK;

$endpoint = 'http://localhost:8000';
$clientId = 'c64b12723aefb65a88ce';
$clientSecret = 'c0c9d483a87332751b2564635765d71c9f6a2e83';
$certificate = file_get_contents(__DIR__ . '/public_key.pem');
$organizationName = 'built-in';
$applicationName = 'app-built-in';

$sdk = new CasdoorSDK(
    $endpoint,
    $clientId,
    $clientSecret,
    $certificate,
    $organizationName,
    $applicationName
);
```

## 🔐 Authentication

### OAuth 2.0 Flow

The SDK provides complete support for the OAuth 2.0 authorization code flow:

#### Step 1: Redirect User to Sign-In Page

```php
$redirectUri = 'http://your-app.com/callback';
$signinUrl = $sdk->auth()->getSigninUrl($redirectUri);

// Redirect user to $signinUrl
header('Location: ' . $signinUrl);
exit;
```

#### Step 2: Handle OAuth Callback

```php
// In your callback handler (e.g., /callback)
$code = $_GET['code'];
$state = $_GET['state'];

try {
    // Exchange authorization code for access token
    $token = $sdk->auth()->getOAuthToken($code, $state);
    
    // Token contains:
    // - $token->accessToken: The JWT access token
    // - $token->refreshToken: The refresh token
    // - $token->expiresIn: Token expiration time in seconds
    // - $token->tokenType: Token type (usually "Bearer")
    
    // Store token in session
    $_SESSION['access_token'] = $token->accessToken;
    $_SESSION['refresh_token'] = $token->refreshToken;
    
} catch (Exception $e) {
    echo "Authentication failed: " . $e->getMessage();
}
```

#### Step 3: Parse JWT Token

```php
$accessToken = $_SESSION['access_token'];

try {
    $claims = $sdk->jwt()->parseJwtToken($accessToken);
    
    // Access user information from claims
    echo "User ID: " . $claims->id . "\n";
    echo "Username: " . $claims->name . "\n";
    echo "Display Name: " . $claims->displayName . "\n";
    echo "Email: " . $claims->email . "\n";
    echo "Phone: " . $claims->phone . "\n";
    
    // Convert claims to User object
    $user = $claims->toUser();
    
} catch (Exception $e) {
    echo "Token parsing failed: " . $e->getMessage();
}
```

#### Step 4: Refresh Token

```php
$refreshToken = $_SESSION['refresh_token'];

try {
    $newToken = $sdk->auth()->refreshOAuthToken($refreshToken);
    
    // Update stored tokens
    $_SESSION['access_token'] = $newToken->accessToken;
    $_SESSION['refresh_token'] = $newToken->refreshToken;
    
} catch (Exception $e) {
    echo "Token refresh failed: " . $e->getMessage();
}
```

### Get Sign-Up URL

```php
// Password-based sign-up page
$signupUrl = $sdk->auth()->getSignupUrl(true);

// OAuth-based sign-up page
$signupUrl = $sdk->auth()->getSignupUrl(false, 'http://your-app.com/callback');
```

### Get User Profile URLs

```php
// Get URL for a specific user's profile page
$profileUrl = $sdk->auth()->getUserProfileUrl('username', $accessToken);

// Get URL for the current user's profile page
$myProfileUrl = $sdk->auth()->getMyProfileUrl($accessToken);
```

## 👥 User Management

### Get Users

```php
// Get all users in the organization
$users = $sdk->users()->getUsers();

foreach ($users as $user) {
    echo "User: " . $user->name . " (" . $user->email . ")\n";
}
```

### Get Users with Pagination

```php
$page = 1;
$pageSize = 10;

$result = $sdk->users()->getPaginationUsers($page, $pageSize);

echo "Total users: " . $result['totalCount'] . "\n";
foreach ($result['users'] as $user) {
    echo "User: " . $user->name . "\n";
}
```

### Get Sorted Users

```php
// Get users sorted by a specific field with a limit
$users = $sdk->users()->getSortedUsers('created_time', 100);
```

### Get User Count

```php
// Get total user count
$totalCount = $sdk->users()->getUserCount();

// Get online user count
$onlineCount = $sdk->users()->getUserCount('true');

// Get offline user count
$offlineCount = $sdk->users()->getUserCount('false');
```

### Get a Specific User

```php
// Get user by name
$user = $sdk->users()->getUser('username');

// Get user by email
$user = $sdk->users()->getUserByEmail('user@example.com');

// Get user by phone
$user = $sdk->users()->getUserByPhone('+1234567890');

// Get user by user ID
$user = $sdk->users()->getUserByUserId('user-id-123');
```

### Add a New User

```php
use Casdoor\Entities\User;

$user = new User();
$user->owner = 'built-in';
$user->name = 'new-user';
$user->displayName = 'New User';
$user->email = 'newuser@example.com';
$user->phone = '+1234567890';
$user->password = 'secure-password';
$user->type = 'normal-user';

$success = $sdk->users()->addUser($user);

if ($success) {
    echo "User created successfully\n";
}
```

### Update a User

```php
// Get the user
$user = $sdk->users()->getUser('username');

// Modify user properties
$user->displayName = 'Updated Name';
$user->email = 'updated@example.com';
$user->phone = '+9876543210';

// Update the user
$success = $sdk->users()->updateUser($user);

if ($success) {
    echo "User updated successfully\n";
}
```

### Update Specific User Fields

```php
$user = $sdk->users()->getUser('username');
$user->displayName = 'New Display Name';

// Only update the displayName field
$success = $sdk->users()->updateUserForColumns($user, ['displayName']);
```

### Delete a User

```php
$user = $sdk->users()->getUser('username');

$success = $sdk->users()->deleteUser($user);

if ($success) {
    echo "User deleted successfully\n";
}
```

### Set User Password

```php
$success = $sdk->users()->setPassword(
    'built-in',       // owner
    'username',       // name
    'old-password',   // oldPassword (can be empty for admin operations)
    'new-password'    // newPassword
);
```

### Check User Password

```php
$user = new User();
$user->owner = 'built-in';
$user->name = 'username';
$user->password = 'password-to-check';

$isValid = $sdk->users()->checkUserPassword($user);

if ($isValid) {
    echo "Password is correct\n";
} else {
    echo "Password is incorrect\n";
}
```

## 📚 Examples

### Complete Authentication Example

```php
<?php

session_start();
require_once 'vendor/autoload.php';

use Casdoor\CasdoorSDK;

// Initialize SDK
$sdk = new CasdoorSDK(
    'http://localhost:8000',
    'your-client-id',
    'your-client-secret',
    file_get_contents('public_key.pem'),
    'your-organization',
    'your-application'
);

// Handle OAuth callback
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $state = $_GET['state'];
    
    try {
        // Get access token
        $token = $sdk->auth()->getOAuthToken($code, $state);
        $_SESSION['access_token'] = $token->accessToken;
        
        // Parse token and get user info
        $claims = $sdk->jwt()->parseJwtToken($token->accessToken);
        $_SESSION['user_name'] = $claims->name;
        
        // Redirect to home page
        header('Location: /home.php');
        exit;
        
    } catch (Exception $e) {
        die("Authentication failed: " . $e->getMessage());
    }
}

// Check if user is logged in
if (isset($_SESSION['access_token'])) {
    $userName = $_SESSION['user_name'];
    $user = $sdk->users()->getUser($userName);
    
    echo "Welcome, " . $user->displayName . "!";
} else {
    // Redirect to sign-in
    $signinUrl = $sdk->auth()->getSigninUrl('http://your-app.com/callback.php');
    header('Location: ' . $signinUrl);
    exit;
}
```

### User Management Example

```php
<?php

require_once 'vendor/autoload.php';

use Casdoor\CasdoorSDK;
use Casdoor\Entities\User;

$sdk = new CasdoorSDK(
    'http://localhost:8000',
    'your-client-id',
    'your-client-secret',
    file_get_contents('public_key.pem'),
    'your-organization',
    'your-application'
);

// Create a new user
$user = new User();
$user->owner = 'your-organization';
$user->name = 'john_doe';
$user->displayName = 'John Doe';
$user->email = 'john@example.com';
$user->phone = '+1234567890';
$user->password = 'SecurePassword123!';
$user->type = 'normal-user';

if ($sdk->users()->addUser($user)) {
    echo "User created successfully!\n";
    
    // Fetch the created user
    $fetchedUser = $sdk->users()->getUser('john_doe');
    
    // Update user
    $fetchedUser->displayName = 'John Smith';
    if ($sdk->users()->updateUser($fetchedUser)) {
        echo "User updated successfully!\n";
    }
    
    // Get all users
    $allUsers = $sdk->users()->getUsers();
    echo "Total users: " . count($allUsers) . "\n";
    
    // Delete user
    if ($sdk->users()->deleteUser($fetchedUser)) {
        echo "User deleted successfully!\n";
    }
}
```

## 📖 Documentation

For more information about Casdoor and its features, please visit:

- **Official Website**: https://casdoor.org
- **Documentation**: https://casdoor.org/docs/overview
- **SDK Documentation**: https://casdoor.org/docs/how-to-connect/sdk
- **API Reference**: https://casdoor.org/docs/api/overview

## 🤝 Contributing

We welcome contributions to the Casdoor PHP SDK! Here's how you can help:

### Reporting Issues

If you find a bug or have a feature request, please create an issue on GitHub:
https://github.com/casdoor/casdoor-php-sdk/issues

### Pull Requests

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Setup

```bash
# Clone the repository
git clone https://github.com/casdoor/casdoor-php-sdk.git
cd casdoor-php-sdk

# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit
```

### Code Style

This project follows PSR-12 coding standards. Please ensure your code adheres to these standards.

## 💬 Community

- **Discord**: https://discord.gg/5rPsrAzK7S
- **GitHub Discussions**: https://github.com/casdoor/casdoor/discussions
- **Forum**: https://forum.casbin.com

## 📄 License

This project is licensed under the Apache License 2.0 - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

This SDK is inspired by the [Casdoor Go SDK](https://github.com/casdoor/casdoor-go-sdk) and follows similar patterns and structures.

## 🔗 Related Projects

- [Casdoor](https://github.com/casdoor/casdoor) - The main Casdoor project
- [Casdoor Go SDK](https://github.com/casdoor/casdoor-go-sdk)
- [Casdoor Java SDK](https://github.com/casdoor/casdoor-java-sdk)
- [Casdoor Python SDK](https://github.com/casdoor/casdoor-python-sdk)
- [Casdoor Node.js SDK](https://github.com/casdoor/casdoor-nodejs-sdk)
