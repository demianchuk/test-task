<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GitControllerTest extends WebTestCase
{
    public function testGetAllStatistics()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/api/statistics/KnpLabs/php-github-api'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('stars', $data);
        $this->assertArrayHasKey('forks', $data);
        $this->assertArrayHasKey('subscribers', $data);
        $this->assertArrayHasKey('closed_pull_requests', $data);
        $this->assertArrayHasKey('open_pull_requests', $data);
        $this->assertArrayHasKey('last_release_date', $data);
        $this->assertArrayHasKey('last_update_date', $data);
        $this->assertArrayHasKey('last_pull_request_merge_date', $data);

    }

}
