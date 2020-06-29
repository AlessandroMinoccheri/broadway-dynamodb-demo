<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 2018-12-30
 * Time: 10:04
 */

namespace App\Tests\Infrastructure\Delivery\Http;


class PostControllerTest extends ApiTestCase
{
    public function testCreatePost()
    {

        $this->client->request('POST',
            '/posts',
            [
                'title' => 'title',
                'description' => 'description'
            ]
        );

        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('', $this->client->getResponse()->getContent());
    }
}