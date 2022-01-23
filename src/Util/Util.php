<?php

declare(strict_types=1);

namespace Casdoor\Util;

use Casdoor\Auth\AuthConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\StreamInterface;
use stdClass;

/**
 * Class Util.
 *
 * @author ab1652759879@gmail.com
 */
class Util
{
    /**
     * doGetStream is a general function to get response from param url through HTTP Get method.
     *
     * @param string     $url
     * @param AuthConfig $authConfig
     *
     * @return StreamInterface
     */
    public static function doGetStream(string $url, AuthConfig $authConfig): StreamInterface
    {
        $client = new Client();
        $credentials = base64_encode("{$authConfig->clientId}:{$authConfig->clientSecret}");
        $headers = [
            'Authorization' => 'Basic ' . $credentials,
        ];
        $request = new Request('GET', $url, $headers);
        $response = $client->send($request);
        $stream = $response->getBody();
        return $stream;
    }

    public static function getUrl(string $action, array $queryMap, AuthConfig $authConfig): string
    {
        $query = '';
        foreach ($queryMap as $k => $v) {
            $query .= sprintf('%s=%s&', $k, $v);
        }
        $query = rtrim($query, '&');

        $url = sprintf('%s/api/%s?%s', $authConfig->endpoint, $action, $query);
        return $url;
    }

    public static function createForm($formData)
    {
        $res = [];
        $formData = json_decode($formData, true);
        foreach ($formData as $k => $v) {
            $res[] = [
                'name'     => $k,
                'contents' => $v,
                'filename' => 'file'
            ];
        }
        return $res;
    }

    public static function doPost(string $action, array $queryMap, AuthConfig $authConfig, $postData, bool $isFile): stdClass
    {
        $url = self::getUrl($action, $queryMap, $authConfig);

        $client = new Client();
        $credentials = base64_encode("{$authConfig->clientId}:{$authConfig->clientSecret}");

        if ($isFile) {
            $resp = $client->request('POST', $url, [
                'headers'   => [
                    'Authorization' => 'Basic ' . $credentials
                ],
                'multipart' => self::createForm($postData)
            ]);
        } else {
            $resp = $client->request('POST', $url, [
                'headers' => [
                    'content-type'  => 'text/plain;charset=UTF-8',
                    'Authorization' => 'Basic ' . $credentials
                ],
                'body'   => $postData
            ]);
        }

        $respStream = $resp->getBody();
        $response = json_decode($respStream->__toString());
        return $response;
    }
}
