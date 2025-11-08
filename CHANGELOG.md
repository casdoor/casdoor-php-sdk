# Changelog

All notable changes to the Casdoor PHP SDK will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- **BREAKING**: Complete rewrite of the SDK based on casdoor-go-sdk architecture
- **BREAKING**: New namespace structure (`Casdoor\` instead of previous structure)
- **BREAKING**: New API interface - see README for migration guide
- Upgraded to modern PHP (requires PHP 7.4+)
- Switched to firebase/php-jwt for JWT handling
- Improved OAuth 2.0 implementation
- Better error handling and exceptions
- PSR-4 autoloading compliance

### Added
- New `CasdoorSDK` facade class for easier SDK usage
- Comprehensive service classes (AuthService, JwtService, UserService)
- Full support for OAuth 2.0 authorization code flow
- Token refresh functionality
- JWT parsing with support for multiple algorithms (RS256, RS512, ES256, ES512)
- User management operations (CRUD)
- Pagination support for user queries
- Comprehensive test suite
- Examples directory with usage examples
- CI/CD workflow for automated testing
- Complete documentation in README

### Removed
- Old implementation files (replaced with new architecture)
- Dependency on league/oauth2-client (using direct implementation)

## [Previous Versions]

Previous versions of this SDK are not documented here. Please refer to the Git history for details on older releases.
