<?php

namespace Acme\Bundle\IotBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiV11ControllerTest extends WebTestCase
{
    public function testReceiveData()
    {
        $client = static::createClient();

        $queryStr = '70c9fb4c4bc5c93b9647d2ad9c80d9406acd9671' . 
                    'edafbb1f35267678588bcda8e714d1b5d0629b42' . 
                    '5934e243538feb102441c7a594e0159fb3f1d585' . 
                    'afa5dc920accce9167607607114c46111b5069fe' . 
                    '3377a0e2dea639acd176af2e18e17e24203888e6' . 
                    'f744964f412283a9';

        $crawler = $client->request('GET', 
                                    'iotdev/v1.1/send?' . $queryStr
                                    );

        // $crawler = $client->request('GET', '/');

        printf("\nTest Get URL success: \n" . $client->getRequest()->getUri() . "\n");

        $this->assertEquals(
                            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
                            $client->getResponse()->getStatusCode()
                            );
    }

    public function testParseData()
    {

    }

    /**
     * @dataProvider getDecrptStrings
     */
    public function testApiV11Send($string, $key, $expected_string)
    {
        static::bootKernel();
        $security = static::$kernel->getContainer()->get('acme.iot.security');
        $decryptStr = $security->decryptAlert($string);

        printf("\nTest Security encrypt str: \n" . "string:" . $string . "\nexpected string:" . $expected_string . "\n");
        $this->assertEquals($expected_string, $decryptStr);

        $client = static::createClient();
        $crawler = $client->request('GET', 
                                    'iotdev/v1.1/send?' . $string
                                    );

        $result = $this->removeSpace($client->getResponse()->getContent());
        $correct_result = preg_match('/^result:[A-Za-z0-9]{10,}/i', $result);
        

        printf("\nTest Correct response format:\n" . $result . "\n");
        $this->assertEquals(true, $correct_result);

        $result_code = explode(':', $result)[1];
        $decryptResult = $security->decryptAlert($result_code);
        printf("Test Response code:\n" . $decryptResult . "\n");
    }

    /**
     * @dataProvider getIncorrectDecrptStrings
     */
    public function testApiV11IncorrectSend($string, $key)
    {
        static::bootKernel();
        $security = static::$kernel->getContainer()->get('acme.iot.security');
        $decryptStr = $security->decryptAlert($string);

        $client = static::createClient();
        $crawler = $client->request('GET', 
                                    'iotdev/v1.1/send?' . $string
                                    );

        $result = $this->removeSpace($client->getResponse()->getContent());
        $correct_result = preg_match('/^result:[A-Za-z0-9]{10,}/i', $result);        

        printf("\nTest Correct response format:\n" . $result . "\n");
        $this->assertEquals(true, $correct_result);

        $result_code = explode(':', $result)[1];
        $decryptResult = $security->decryptAlert($result_code);
        printf("Test Response code:\n" . $decryptResult . "\n");
    }

    /**
     * @dataProvider getDecrptStrings
     */
    public function testApiV11Observer($string, $key, $expected_string)
    {
        printf("\nTest ApiV11 Observer:\n");
        $client = static::createClient();
        $crawler = $client->request('GET', 
                                    '/iot/api/test/decorator/?' . $string
                                    );
        printf("\nTest Get URL success: %s\n", $client->getRequest()->getUri());
        $this->assertEquals(
                            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
                            $client->getResponse()->getStatusCode()
                            );

        $result = $this->removeSpace($client->getResponse()->getContent());
        $correct_result = preg_match('/^result:[A-Za-z0-9]{10,}/i', $result);        

        printf("\nTest Correct response format:\n" . $result . "\n");
        $this->assertEquals(true, $correct_result);
    }


    public function getEncrptStrings()
    {
        return array(
                    array('sn=asdf12345&value=1-10-1461041182123_2-20-1461041182123&vender=jimupai&model=JMA01&random=1c2a448f15ce5149',
                          'TTuuIvb76TY123Ki',
                          '70c9fb4c4bc5c93b9647d2ad9c80d9406acd9671edafbb1f35267678588bcda8e714d1b5d0629b425934e243538feb102441c7a594e0159fb3f1d585afa5dc920accce9167607607114c46111b5069fe3377a0e2dea639acd176af2e18e17e24203888e6f744964f412283a9')
        );
    }

    public function getDecrptStrings()
    {
        return array(
                    array('70c9fb4c4bc5c93b9647d2ad9c80d9406acd9671edafbb1f35267678588bcda8e714d1b5d0629b425934e243538feb102441c7a594e0159fb3f1d585afa5dc920accce9167607607114c46111b5069fe3377a0e2dea639acd176af2e18e17e24203888e6f744964f412283a9',
                          'TTuuIvb76TY123Ki',
                          'sn=asdf12345&value=1-10-1461041182123_2-20-1461041182123&vender=jimupai&model=JMA01&random=1c2a448f15ce5149')
        );
    }

    public function getIncorrectDecrptStrings()
    {
        return array(
                    array('4bc5c93b9647d2ad9c80d9406acd9671e5267678588bcda8e714d1b5d0629b425934e243538feb102441c7a594e0159fb3f1d585afa5dc920accce9167607607114c46111b5069fe3377a0e2dea639acd176af2e18e17e24203888e6f744964f412283a9',
                          'Wrongkey')
        );
    }

    private function removeSpace($str)
    {
        return strtolower(preg_replace("/\s+|　/", "", urldecode($str)));
    }

    /*
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/device/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /device/");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'acme_bundle_iotbundle_device[field_name]'  => 'Test',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'acme_bundle_iotbundle_device[field_name]'  => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

    */
}
