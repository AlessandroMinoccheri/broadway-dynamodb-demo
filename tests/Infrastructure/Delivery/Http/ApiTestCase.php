<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 2018-12-30
 * Time: 10:05
 */

namespace App\Tests\Infrastructure\Delivery\Http;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTestCase extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\Client */
    protected $client;

    public function getContainer()
    {
        if (!$this->client) {
            throw new \Exception('Client NULL');
        }

        return $this->client->getContainer();
    }

    protected function setUp()
    {
        $this->client = static::createClient(array(
            'environment' => 'test',
            'debug' => false,
        ));
    }
}
