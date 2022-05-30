<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbApiConsumer
{
    private HttpClientInterface $httpClient;

    private string $apiUrl;

    private string $apiKey;

    /**
     * @param HttpClientInterface $httpClient
     * @param string $apiUrl
     * @param string $apiKey
     */
    public function __construct(HttpClientInterface $httpClient, string $apiUrl, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $title
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function fetchMovieByTitle(string $title) : ?array {
        $data = ['apikey' => $this->apiKey, 't' => $title];
        $result = $this->httpClient->request(Request::METHOD_GET, $this->apiUrl, ['query' => $data])->toArray();

        if ($result['Response'] == 'False') {
            return null;
        }

        return $result;
    }
}