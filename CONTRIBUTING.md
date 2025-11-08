# Contributing to Casdoor PHP SDK

Thank you for your interest in contributing to the Casdoor PHP SDK! This document provides guidelines and instructions for contributing.

## Code of Conduct

This project adheres to the [Contributor Covenant Code of Conduct](https://www.contributor-covenant.org/). By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When creating a bug report, include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples** to demonstrate the steps
- **Describe the behavior you observed** and what you expected to see
- **Include code samples** and error messages
- **Specify your environment**: PHP version, OS, Casdoor version

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Use a clear and descriptive title**
- **Provide a detailed description** of the suggested enhancement
- **Explain why this enhancement would be useful** to most users
- **List any alternative solutions** you've considered

### Pull Requests

1. **Fork the repository** and create your branch from `master`
2. **Follow the coding style** used throughout the project
3. **Write clear commit messages** following conventional commits format
4. **Add tests** for any new functionality
5. **Update documentation** as needed
6. **Ensure all tests pass** before submitting

## Development Setup

### Prerequisites

- PHP 7.4 or higher
- Composer
- Git

### Setup Steps

```bash
# Clone your fork
git clone https://github.com/your-username/casdoor-php-sdk.git
cd casdoor-php-sdk

# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit
```

## Coding Standards

### PHP Standards

This project follows:
- **PSR-4** for autoloading
- **PSR-12** for coding style
- **Type declarations** for all parameters and return types (PHP 7.4+)

### Code Style

- Use 4 spaces for indentation (no tabs)
- Use meaningful variable and function names
- Add PHPDoc comments for all classes and methods
- Keep methods focused and reasonably short
- Follow SOLID principles

### Example

```php
<?php

namespace Casdoor\Services;

use Casdoor\Client;

/**
 * ExampleService demonstrates proper coding style
 */
class ExampleService
{
    private Client $client;

    /**
     * Create a new ExampleService instance
     *
     * @param Client $client The Casdoor client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Example method with proper documentation
     *
     * @param string $param The parameter description
     * @return array<string, mixed> The return value description
     * @throws \Exception When something goes wrong
     */
    public function exampleMethod(string $param): array
    {
        // Implementation
        return [];
    }
}
```

## Testing

### Running Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test
./vendor/bin/phpunit tests/CasdoorSDKTest.php

# Run with coverage (requires Xdebug)
XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html coverage
```

### Writing Tests

- Write tests for all new functionality
- Use descriptive test method names: `testMethodNameBehavior`
- Include both positive and negative test cases
- Mock external dependencies when appropriate
- Keep tests focused and independent

## Commit Messages

Follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

```
<type>(<scope>): <subject>

<body>

<footer>
```

Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

Example:
```
feat(auth): add token refresh functionality

Implement automatic token refresh when access token expires.
This improves user experience by maintaining authentication state.

Closes #123
```

## Documentation

When adding or changing functionality:

1. **Update README.md** with usage examples
2. **Add PHPDoc comments** to all public methods
3. **Update CHANGELOG.md** with changes
4. **Add examples** in the `examples/` directory if appropriate

## Release Process

Releases are managed by project maintainers:

1. Update version in relevant files
2. Update CHANGELOG.md
3. Create a new release on GitHub
4. Tag follows semantic versioning (e.g., v1.2.3)

## Getting Help

- **Discord**: Join our [Discord server](https://discord.gg/5rPsrAzK7S)
- **Issues**: Open an issue on GitHub
- **Documentation**: Check the [official documentation](https://casdoor.org/docs/)

## License

By contributing to Casdoor PHP SDK, you agree that your contributions will be licensed under the Apache License 2.0.

## Questions?

Feel free to reach out on our Discord server or open an issue if you have any questions about contributing!

## Thank You!

Your contributions are greatly appreciated and help make Casdoor PHP SDK better for everyone!
