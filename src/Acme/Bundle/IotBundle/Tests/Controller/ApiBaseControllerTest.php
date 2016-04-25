<?php

namespace Acme\Bundle\IotBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiBaseControllerTest extends WebTestCase
{
    public function testCheckdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/iot/api/checkdate');
        $test_time = time();
        $response_time = $client->getResponse()->getContent();

        printf("\nTest case api for check date:");
        $print_str = printf("\ntime now: %s, response time: %s\n", $test_time, $response_time);
        // echo $print_str;

        $diff = abs($test_time - $response_time) <= 5;

        $this->assertTrue($diff);
    }
}
