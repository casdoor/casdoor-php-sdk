# Casdoor PHP SDK Examples

This directory contains example code demonstrating how to use the Casdoor PHP SDK.

## Available Examples

### basic_usage.php

Demonstrates basic SDK operations including:
- SDK initialization
- OAuth URL generation
- Token exchange and JWT parsing
- User management operations

**Run the example:**

```bash
php examples/basic_usage.php
```

**Configure with environment variables:**

```bash
export CASDOOR_ENDPOINT="http://localhost:8000"
export CASDOOR_CLIENT_ID="your-client-id"
export CASDOOR_CLIENT_SECRET="your-client-secret"
export CASDOOR_ORGANIZATION="your-organization"
export CASDOOR_APPLICATION="your-application"

php examples/basic_usage.php
```

## Prerequisites

Before running the examples, make sure you have:

1. **Installed dependencies:**
   ```bash
   composer install
   ```

2. **A running Casdoor instance** (for live examples)

3. **Valid credentials** from your Casdoor application

4. **Certificate file** (public_key.pem) in the `tests/` directory

## Creating Your Own Examples

When creating your own examples or integration:

1. Include the autoloader:
   ```php
   require_once 'vendor/autoload.php';
   ```

2. Import the necessary classes:
   ```php
   use Casdoor\CasdoorSDK;
   use Casdoor\Entities\User;
   ```

3. Initialize the SDK:
   ```php
   $sdk = new CasdoorSDK(
       $endpoint,
       $clientId,
       $clientSecret,
       $certificate,
       $organizationName,
       $applicationName
   );
   ```

4. Use the service methods:
   ```php
   $signinUrl = $sdk->auth()->getSigninUrl('http://localhost/callback');
   $users = $sdk->users()->getUsers();
   ```

## Common Patterns

### Authentication Flow

```php
// 1. Redirect to Casdoor login
$signinUrl = $sdk->auth()->getSigninUrl('http://your-app.com/callback');
header('Location: ' . $signinUrl);

// 2. Handle OAuth callback
$code = $_GET['code'];
$state = $_GET['state'];
$token = $sdk->auth()->getOAuthToken($code, $state);

// 3. Store token in session
$_SESSION['access_token'] = $token->accessToken;

// 4. Parse token to get user info
$claims = $sdk->jwt()->parseJwtToken($token->accessToken);
```

### User Management

```php
// Create a user
$user = new User();
$user->owner = 'org-name';
$user->name = 'username';
$user->email = 'user@example.com';
$sdk->users()->addUser($user);

// Read a user
$user = $sdk->users()->getUser('username');

// Update a user
$user->displayName = 'New Name';
$sdk->users()->updateUser($user);

// Delete a user
$sdk->users()->deleteUser($user);
```

## Error Handling

Always wrap SDK calls in try-catch blocks:

```php
try {
    $token = $sdk->auth()->getOAuthToken($code, $state);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Support

For more information:
- [Main Documentation](https://casdoor.org/docs/)
- [API Reference](https://casdoor.org/docs/api/)
- [GitHub Issues](https://github.com/casdoor/casdoor-php-sdk/issues)
- [Discord Community](https://discord.gg/5rPsrAzK7S)
