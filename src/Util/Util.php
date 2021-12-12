<?php

declare(strict_types=1);

namespace Casdoor\Util;

use Casdoor\Auth\AuthConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\StreamInterface;
use stdClass;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Util.
 *
 * @author ab1652759879@gmail.com
 */
class Util
{
    public static function doGetStream(string $url): StreamInterface
    {
        $client = new Client();
        $request = new Request('GET', $url);
        $response = $client->send($request);
        $stream = $response->getBody();
        return $stream;
    }

    public static function getUrl(string $action, array $queryMap, $authConfig): string
    {
        $query = '';
        foreach ($queryMap as $k => $v) {
            $query .= sprintf('%s=%s&', $k, $v);
        }
    
        $url = sprintf('%s/api/%s?%sclientId=%s&clientSecret=%s', $authConfig->endpoint, $action, $query, $authConfig->clientId, $authConfig->clientSecret);
        return $url;
    }

    public static function doPost(string $action, array $queryMap, AuthConfig $authConfig, $postData): stdClass
    {
        $url = self::getUrl($action, $queryMap, $authConfig);

        $client = new Client();
        $request = new Request('POST', $url, ['content-type' => 'text/plain;charset=UTF-8'], $postData);
        try {
            $resp = $client->send($request);
        } catch (GuzzleException $e) {
            return null;
        }
        $respStream = $resp->getBody();
        $response = json_decode($respStream->__toString());
        return $response;
    }
}
