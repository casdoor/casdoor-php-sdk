<?php

// Copyright 2023 The Casdoor Authors. All Rights Reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace Casdoor;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Client is the core client for interacting with Casdoor server
 */
class Client
{
    /**
     * @var string The endpoint URL of Casdoor server
     */
    public string $endpoint;

    /**
     * @var string The client ID of the application
     */
    public string $clientId;

    /**
     * @var string The client secret of the application
     */
    public string $clientSecret;

    /**
     * @var string The certificate content in x509 format
     */
    public string $certificate;

    /**
     * @var string The organization name
     */
    public string $organizationName;

    /**
     * @var string The application name
     */
    public string $applicationName;

    /**
     * @var HttpClient The HTTP client instance
     */
    private HttpClient $httpClient;

    /**
     * Create a new Casdoor client instance
     *
     * @param string $endpoint The endpoint URL of Casdoor server
     * @param string $clientId The client ID
     * @param string $clientSecret The client secret
     * @param string $certificate The certificate content
     * @param string $organizationName The organization name
     * @param string $applicationName The application name
     */
    public function __construct(
        string $endpoint,
        string $clientId,
        string $clientSecret,
        string $certificate,
        string $organizationName,
        string $applicationName
    ) {
        $this->endpoint = rtrim($endpoint, '/');
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->certificate = $certificate;
        $this->organizationName = $organizationName;
        $this->applicationName = $applicationName;
        $this->httpClient = new HttpClient([
            'base_uri' => $this->endpoint,
            'timeout' => 30.0,
        ]);
    }

    /**
     * Set a custom HTTP client
     *
     * @param HttpClient $httpClient
     */
    public function setHttpClient(HttpClient $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Build a URL for API endpoint with query parameters
     *
     * @param string $action The API action
     * @param array<string, string> $queryParams Query parameters
     * @return string The complete URL
     */
    public function getUrl(string $action, array $queryParams = []): string
    {
        $query = http_build_query($queryParams);
        $url = sprintf('%s/api/%s', $this->endpoint, $action);
        
        if (!empty($query)) {
            $url .= '?' . $query;
        }
        
        return $url;
    }

    /**
     * Get ID in the format "organization/name"
     *
     * @param string $name The name
     * @return string The ID
     */
    public function getId(string $name): string
    {
        return $this->organizationName . '/' . $name;
    }

    /**
     * Execute a GET request and return Response object
     *
     * @param string $url The URL to request
     * @return Response The response object
     * @throws \Exception
     */
    public function doGetResponse(string $url): Response
    {
        $bytes = $this->doGetBytesRaw($url);
        $data = json_decode($bytes, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to decode JSON response: ' . json_last_error_msg());
        }
        
        $response = new Response(
            $data['status'] ?? 'error',
            $data['msg'] ?? '',
            $data['data'] ?? null,
            $data['data2'] ?? null
        );
        
        if ($response->status !== 'ok') {
            throw new \Exception($response->msg);
        }
        
        return $response;
    }

    /**
     * Execute a GET request and return data as JSON bytes
     *
     * @param string $url The URL to request
     * @return string The response data as JSON string
     * @throws \Exception
     */
    public function doGetBytes(string $url): string
    {
        $response = $this->doGetResponse($url);
        return json_encode($response->data);
    }

    /**
     * Execute a GET request and return raw bytes
     *
     * @param string $url The URL to request
     * @return string The raw response body
     * @throws \Exception
     */
    public function doGetBytesRaw(string $url): string
    {
        try {
            $response = $this->httpClient->get($url, [
                'auth' => [$this->clientId, $this->clientSecret],
            ]);
            
            $statusCode = $response->getStatusCode();
            $body = (string)$response->getBody();
            
            if ($statusCode !== 200 && $statusCode !== 403) {
                throw new \Exception("HTTP request failed with status $statusCode: $body");
            }
            
            return $body;
        } catch (GuzzleException $e) {
            throw new \Exception('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Execute a POST request
     *
     * @param string $action The API action
     * @param array<string, string> $queryParams Query parameters
     * @param mixed $postData Data to post
     * @param bool $isForm Whether to post as form data
     * @param bool $isFile Whether posting a file
     * @return Response The response object
     * @throws \Exception
     */
    public function doPost(
        string $action,
        array $queryParams = [],
        $postData = null,
        bool $isForm = false,
        bool $isFile = false
    ): Response {
        $url = $this->getUrl($action, $queryParams);
        
        $options = [
            'auth' => [$this->clientId, $this->clientSecret],
        ];
        
        if ($isForm) {
            if ($isFile) {
                $options['multipart'] = [
                    [
                        'name' => 'file',
                        'contents' => $postData,
                        'filename' => 'file',
                    ],
                ];
            } else {
                $options['form_params'] = is_array($postData) ? $postData : json_decode($postData, true);
            }
        } else {
            $options['headers'] = ['Content-Type' => 'text/plain;charset=UTF-8'];
            $options['body'] = is_string($postData) ? $postData : json_encode($postData);
        }
        
        try {
            $response = $this->httpClient->post($url, $options);
            $body = (string)$response->getBody();
            $data = json_decode($body, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to decode JSON response: ' . json_last_error_msg());
            }
            
            $responseObj = new Response(
                $data['status'] ?? 'error',
                $data['msg'] ?? '',
                $data['data'] ?? null,
                $data['data2'] ?? null
            );
            
            if ($responseObj->status !== 'ok') {
                throw new \Exception($responseObj->msg);
            }
            
            return $responseObj;
        } catch (GuzzleException $e) {
            throw new \Exception('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Execute a POST request and return raw bytes
     *
     * @param string $url The URL to request
     * @param string $contentType Content type header
     * @param mixed $body Request body
     * @return string The raw response body
     * @throws \Exception
     */
    public function doPostBytesRaw(string $url, string $contentType, $body): string
    {
        try {
            $response = $this->httpClient->post($url, [
                'auth' => [$this->clientId, $this->clientSecret],
                'headers' => ['Content-Type' => $contentType ?: 'text/plain;charset=UTF-8'],
                'body' => $body,
            ]);
            
            $statusCode = $response->getStatusCode();
            $responseBody = (string)$response->getBody();
            
            if ($statusCode !== 200 && $statusCode !== 403) {
                throw new \Exception("HTTP request failed with status $statusCode: $responseBody");
            }
            
            return $responseBody;
        } catch (GuzzleException $e) {
            throw new \Exception('HTTP request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Modify a resource (generic method for add/update/delete operations)
     *
     * @param string $action The API action (add-*, update-*, delete-*)
     * @param mixed $resource The resource object
     * @param array|null $columns Specific columns to update (optional)
     * @return array{Response, bool} Response and whether operation affected the resource
     * @throws \Exception
     */
    protected function modifyResource(string $action, $resource, ?array $columns = null): array
    {
        $queryParams = [];
        if ($columns !== null && !empty($columns)) {
            $queryParams['columns'] = implode(',', $columns);
        }
        
        $postData = json_encode($resource);
        $response = $this->doPost($action, $queryParams, $postData, false, false);
        
        $affected = $response->data === 'Affected';
        return [$response, $affected];
    }
}
