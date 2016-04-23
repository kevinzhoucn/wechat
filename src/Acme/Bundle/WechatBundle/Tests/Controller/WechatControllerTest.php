<?php

namespace Acme\Bundle\IotBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\Bundle\UserBundle\Entity\User;

class WechatControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $client;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->client = static::createClient();
    }

    public function testAirKiss()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/wechat/api/airkiss/');

        printf("\nTest wechat device api airkiss: \n" . $client->getRequest()->getUri() . "\n");
        $this->assertEquals(
                            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
                            $client->getResponse()->getStatusCode());
        printf("Pass! \n");
    }

    public function testBindUser()
    {
        
        $client = static::createClient();
        $crawler = $client->request('GET', '/iot/device/bind/new/');

        printf("\nTest device bind new form: \n" . $client->getRequest()->getUri() . "\n");
        $this->assertEquals(1, $crawler->filter('form')->count());

        $form = $crawler->selectButton('提交')->form();

        // first time add one phone
        $username = $form['bind_device[phone]'] = '18000000001';
        $sn = $form['bind_device[sn]'] = 'sn1234567';
        $crawler = $client->submit($form);

        printf("\nTest new user creat from form: \n");
        $username = '18000000001';
        $user = $this->em
                     ->getRepository('AcmeUserBundle:User')
                     ->findOneBy(array('username' => $username));
        $this->assertEquals(false, !$user);
        printf("Pass! \n");

        printf("\nTest new user has 1 phone number: \n");
        $this->assertEquals(1, count($user->getPhones()));
        printf("Pass! \n");
    }

    public function testAddMorePhones()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/iot/device/bind/new/');

        $form = $crawler->selectButton('提交')->form();
        $username = $form['bind_device[phone]'] = '18000000001';
        $sn = $form['bind_device[sn]'] = 'sn1234567';
        $phone1 = $form['bind_device[phone1]'] = '18000000002';
        $phone2 = $form['bind_device[phone2]'] = '18000000003';

        $crawler = $client->submit($form);

        printf("\nTest new user creat from form: \n");
        $username = '18000000001';
        $user = $this->em
                     ->getRepository('AcmeUserBundle:User')
                     ->findOneBy(array('username' => $username));
        $this->assertEquals(false, !$user);
        printf("Pass! \n");

        printf("\nTest new user has 3 phone number: \n");
        $this->assertEquals(3, count($user->getPhones()));
        printf("Pass! \n");

        printf("\nTest redirect to success page: \n");
        $this->assertTrue($client->getResponse()->isRedirect('/iot/device/bind/success/'));

        $crawler = $client->request('GET', '/iot/device/bind/success/');        
        $this->assertEquals(
                            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
                            $client->getResponse()->getStatusCode());
        printf("Pass! \n");
    }

    public function testBindSuccess()
    {
        // $crawler = $this->client->request('GET', '/iot/device/bind/success/');        
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
    }

//     // public function testReceiveData()
//     // {
//     //     $client = static::createClient();

//     //     $crawler = $client->request('GET', '/iot/');
//     // }

    
//     public function testCompleteScenario()
//     {
//         // Create a new client to browse the application
//         $client = static::createClient();

//         // Create a new entry in the database
//         $crawler = $client->request('GET', '/device/');
//         $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /device/");
//         $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

//         // Fill in the form and submit it
//         $form = $crawler->selectButton('Create')->form(array(
//             'acme_bundle_iotbundle_device[field_name]'  => 'Test',
//             // ... other fields to fill
//         ));

//         $client->submit($form);
//         $crawler = $client->followRedirect();

//         // Check data in the show view
//         $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

//         // Edit the entity
//         $crawler = $client->click($crawler->selectLink('Edit')->link());

//         $form = $crawler->selectButton('Update')->form(array(
//             'acme_bundle_iotbundle_device[field_name]'  => 'Foo',
//             // ... other fields to fill
//         ));

//         $client->submit($form);
//         $crawler = $client->followRedirect();

//         // Check the element contains an attribute with value equals "Foo"
//         $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

//         // Delete the entity
//         $client->submit($crawler->selectButton('Delete')->form());
//         $crawler = $client->followRedirect();

//         // Check the entity has been delete on the list
//         $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
//     }

    
}
