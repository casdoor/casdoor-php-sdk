<?php

/**
 * Basic Usage Example for Casdoor PHP SDK
 * 
 * This example demonstrates the basic usage of the Casdoor PHP SDK
 * for authentication and user management.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Casdoor\CasdoorSDK;
use Casdoor\Entities\User;

// Configuration
$endpoint = getenv('CASDOOR_ENDPOINT') ?: 'http://localhost:8000';
$clientId = getenv('CASDOOR_CLIENT_ID') ?: 'your-client-id';
$clientSecret = getenv('CASDOOR_CLIENT_SECRET') ?: 'your-client-secret';
$organizationName = getenv('CASDOOR_ORGANIZATION') ?: 'your-organization';
$applicationName = getenv('CASDOOR_APPLICATION') ?: 'your-application';

// Load certificate from file
$certificatePath = __DIR__ . '/../tests/public_key.pem';
if (!file_exists($certificatePath)) {
    die("Certificate file not found at: $certificatePath\n");
}
$certificate = file_get_contents($certificatePath);

// Initialize the SDK
$sdk = new CasdoorSDK(
    $endpoint,
    $clientId,
    $clientSecret,
    $certificate,
    $organizationName,
    $applicationName
);

echo "Casdoor PHP SDK Basic Usage Example\n";
echo "====================================\n\n";

// Example 1: Generate Sign-In URL
echo "1. Generate Sign-In URL\n";
echo "   -------------------\n";
$redirectUri = 'http://localhost:8080/callback';
$signinUrl = $sdk->auth()->getSigninUrl($redirectUri);
echo "   Sign-in URL: $signinUrl\n\n";

// Example 2: Generate Sign-Up URL
echo "2. Generate Sign-Up URL\n";
echo "   -------------------\n";
$signupUrl = $sdk->auth()->getSignupUrl(true);
echo "   Sign-up URL: $signupUrl\n\n";

// Example 3: OAuth Token Exchange (requires authorization code)
echo "3. OAuth Token Exchange\n";
echo "   --------------------\n";
echo "   Note: This requires a valid authorization code from OAuth flow\n";
echo "   Example code:\n";
echo "   \$code = \$_GET['code'];\n";
echo "   \$state = \$_GET['state'];\n";
echo "   \$token = \$sdk->auth()->getOAuthToken(\$code, \$state);\n";
echo "   \$accessToken = \$token->accessToken;\n\n";

// Example 4: Parse JWT Token (requires valid token)
echo "4. Parse JWT Token\n";
echo "   ----------------\n";
echo "   Note: This requires a valid JWT token\n";
echo "   Example code:\n";
echo "   \$claims = \$sdk->jwt()->parseJwtToken(\$accessToken);\n";
echo "   echo 'User: ' . \$claims->name;\n";
echo "   echo 'Email: ' . \$claims->email;\n\n";

// Example 5: User Management
echo "5. User Management\n";
echo "   ----------------\n";
echo "   Note: These operations require proper authentication\n\n";

echo "   Get all users:\n";
echo "   \$users = \$sdk->users()->getUsers();\n\n";

echo "   Get user by name:\n";
echo "   \$user = \$sdk->users()->getUser('username');\n\n";

echo "   Get user by email:\n";
echo "   \$user = \$sdk->users()->getUserByEmail('user@example.com');\n\n";

echo "   Create a new user:\n";
echo "   \$user = new User();\n";
echo "   \$user->owner = 'your-organization';\n";
echo "   \$user->name = 'new-user';\n";
echo "   \$user->displayName = 'New User';\n";
echo "   \$user->email = 'newuser@example.com';\n";
echo "   \$user->password = 'SecurePassword123!';\n";
echo "   \$sdk->users()->addUser(\$user);\n\n";

echo "   Update a user:\n";
echo "   \$user->displayName = 'Updated Name';\n";
echo "   \$sdk->users()->updateUser(\$user);\n\n";

echo "   Delete a user:\n";
echo "   \$sdk->users()->deleteUser(\$user);\n\n";

// Example 6: Complete OAuth Flow
echo "6. Complete OAuth Flow Example\n";
echo "   ----------------------------\n";
echo "   // Step 1: Redirect to sign-in\n";
echo "   \$signinUrl = \$sdk->auth()->getSigninUrl('http://localhost/callback');\n";
echo "   header('Location: ' . \$signinUrl);\n\n";

echo "   // Step 2: Handle callback\n";
echo "   \$code = \$_GET['code'];\n";
echo "   \$state = \$_GET['state'];\n";
echo "   \$token = \$sdk->auth()->getOAuthToken(\$code, \$state);\n\n";

echo "   // Step 3: Parse token\n";
echo "   \$claims = \$sdk->jwt()->parseJwtToken(\$token->accessToken);\n\n";

echo "   // Step 4: Get user details\n";
echo "   \$user = \$sdk->users()->getUser(\$claims->name);\n";
echo "   echo 'Welcome, ' . \$user->displayName . '!';\n\n";

echo "Example completed successfully!\n";
echo "\nFor more examples and documentation, visit:\n";
echo "https://github.com/casdoor/casdoor-php-sdk\n";
