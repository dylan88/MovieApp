<?php

namespace App\Tests;

use App\Services\OmdbApiConsumer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NativeHttpClient;

class OmdbApiTest extends TestCase
{
    /**
     * @return void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testApiResponse(): void
    {
        $c = new NativeHttpClient();
        $api = new OmdbApiConsumer($c, 'http://www.omdbapi.com','28c5b7b1');
        $this->assertNull($api->fetchMovieByTitle('foobar'));
        $this->assertIsArray($api->fetchMovieByTitle('star wars'));
    }
}
