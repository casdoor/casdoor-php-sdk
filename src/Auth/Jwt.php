<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Exceptions\CasdoorException;

/**
 * Class Jwt.
 *
 * @author ab1652759879@gmail.com
 */
class Jwt
{
    /**
     * Parse and return a OpenSSLAsymmetricKey (PHP 8.0+) or resource (PHP < 8.0) suitable for verification.
     *
     * @param AuthConfig $authConfig
     *
     * @return \OpenSSLAsymmetricKey|resource
     *
     * @throws CasdoorException When unable to retrieve key. See error message for details.
     */
    private function getKey(AuthConfig $authConfig)
    {
        $key = openssl_pkey_get_public($authConfig->jwtPublicKey);

        if (is_bool($key)) {
            throw new CasdoorException('Cannot verify signature');
        }

        $details = openssl_pkey_get_details($key);

        if ($details === false || $details['type'] !== OPENSSL_KEYTYPE_RSA) {
            throw new CasdoorException('Cannot verify signature: Key uses an incompatible signing algorithm');
        }

        return $key;
    }

    /**
     * Parse json web token
     *
     * @param string $token
     *
     * @return array
     */
    public function parseJwtToken(string $token, AuthConfig $authConfig): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new CasdoorException('The JWT string must contain two dots');
        }

        $headers = $this->decodeHeaders($parts[0]);
        $alg = $headers['alg'] ?? null;
        if ($alg === null) {
            throw new CasdoorException('Provided token is missing a alg header');
        }

        $claims = $this->decodeClaims($parts[1]);
        $payload = "{$parts[0]}.{$parts[1]}";
        $signature = $this->decodeSignature($parts[2]) ?? '';
        $publicKey = $this->getKey($authConfig);

        $valid = openssl_verify($payload, $signature, $publicKey, OPENSSL_ALGO_SHA256);
        if ($valid !== 1) {
            throw new CasdoorException('Cannot verify signature');
        } else {
            return $claims;
        }
    }

    /**
     * Decodes and returns the headers portion of a JWT as an array.
     *
     * @param string $headers String representing the headers portion of the JWT.
     *
     * @return array<int|string>|null
     *
     * @throws \JsonException When headers portion cannot be decoded properly.
     */
    private function decodeHeaders(string $headers): ?array
    {
        $decoded = base64_decode(strtr($headers, '-_', '+/'), true);

        if ($decoded !== false) {
            return json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }

    /**
     * Decodes and returns the claims portion of a JWT as an array.
     *
     * @param string $claims String representing the claims portion of the JWT.
     *
     * @return array<array|int|string>|null
     *
     * @throws \JsonException When claims portion cannot be decoded properly.
     */
    private function decodeClaims(string $claims): ?array
    {
        $decoded = base64_decode(strtr($claims, '-_', '+/'), true);

        if ($decoded !== false) {
            return json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);
        }

        return null;
    }

    /**
     * Decodes and returns the signature portion of a JWT as a string.
     *
     * @param string $signature String representing the signature portion of the JWT.
     *
     * @return string|null
     */
    private function decodeSignature(string $signature): ?string
    {
        $decoded = base64_decode(strtr($signature, '-_', '+/'), true);

        if ($decoded !== false) {
            return $decoded;
        }

        return null;
    }
}
