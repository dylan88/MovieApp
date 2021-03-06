<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HelloControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/hello/test');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'HelloController');
    }
}
