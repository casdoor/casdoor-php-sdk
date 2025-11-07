<?php

declare(strict_types=1);

namespace Casdoor\Auth;

use Casdoor\Util\Util;

/**
 * Class Session.
 *
 * @author ab1652759879@gmail.com
 */
class Session
{
    public $owner;
    public $name;
    public $application;
    public $createdTime;
    public $sessionId;

    /**
     * @var AuthConfig
     */
    public static $authConfig;

    public static function initConfig(
        string $endpoint,
        string $clientId,
        string $clientSecret,
        string $certificate,
        string $organizationName,
        string $applicationName
    ): void {
        self::$authConfig = new AuthConfig(
            $endpoint,
            $clientId,
            $clientSecret,
            $certificate,
            $organizationName,
            $applicationName
        );
    }

    /**
     * Get all sessions.
     *
     * @return array
     */
    public static function getSessions(): array
    {
        $queryMap = [
            'owner' => self::$authConfig->organizationName,
        ];

        $url = Util::getUrl('get-sessions', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $sessions = json_decode($stream->__toString(), true);

        return $sessions;
    }

    /**
     * Get a specific session.
     *
     * @param string $name
     * @param string $application
     *
     * @return array|null
     */
    public static function getSession(string $name, string $application): ?array
    {
        $queryMap = [
            'sessionPkId' => self::$authConfig->organizationName . '/' . $name . '/' . $application,
        ];

        $url = Util::getUrl('get-session', $queryMap, self::$authConfig);
        $stream = Util::doGetStream($url, self::$authConfig);
        $session = json_decode($stream->__toString(), true);

        return $session;
    }

    /**
     * Add a session.
     *
     * @param Session $session
     *
     * @return bool
     */
    public static function addSession(Session $session): bool
    {
        $queryMap = [
            'sessionPkId' => self::$authConfig->organizationName . '/' . $session->name . '/' . $session->application,
        ];

        $session->owner = self::$authConfig->organizationName;
        $postData = json_encode($session);
        $response = Util::doPost('add-session', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Update a session.
     *
     * @param Session $session
     *
     * @return bool
     */
    public static function updateSession(Session $session): bool
    {
        $queryMap = [
            'sessionPkId' => self::$authConfig->organizationName . '/' . $session->name . '/' . $session->application,
        ];

        $session->owner = self::$authConfig->organizationName;
        $postData = json_encode($session);
        $response = Util::doPost('update-session', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }

    /**
     * Delete a session.
     *
     * @param Session $session
     *
     * @return bool
     */
    public static function deleteSession(Session $session): bool
    {
        $queryMap = [
            'sessionPkId' => self::$authConfig->organizationName . '/' . $session->name . '/' . $session->application,
        ];

        $session->owner = self::$authConfig->organizationName;
        $postData = json_encode($session);
        $response = Util::doPost('delete-session', $queryMap, self::$authConfig, $postData, false);

        return $response->data === 'Affected';
    }
}
