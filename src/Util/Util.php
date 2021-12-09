<?php

declare(strict_types=1);

namespace Casdoor\Util;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\StreamInterface;

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
}
