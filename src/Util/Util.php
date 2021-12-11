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
    public static function getStream(string $url): StreamInterface
    {
        $client = new Client();
        $request = new Request('GET', $url);
        $response = $client->send($request);
        $stream = $response->getBody();
        return $stream;
    }

    public static function doPost(string $action, array $queryMap, AuthConfig $authConfig): stdClass
    {
        $query = '';
        foreach ($queryMap as $k => $v) {
            $query .= sprintf('%s=%s&', $k, $v);
        }
        $url = sprintf('%s/api/%s?%sclientId=%s&clientSecret=%s', $authConfig->endpoint, $action, $query, $authConfig->clientId, $authConfig->clientSecret);

        $client = new Client();
        $request = new Request('POST', $url, ['content-type' => 'text/plain;charset=UTF-8']);
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
