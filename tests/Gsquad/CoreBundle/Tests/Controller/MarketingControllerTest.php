<?php

namespace Gsquad\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MarketingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/participez-etude-observation-oiseaux');

        $this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
    }

}
