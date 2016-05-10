<?php

namespace Tests\Utils;

use Acme\Bundle\IotBundle\Service\Observer\JimuObserver\ConcreteSubject;
use Acme\Bundle\IotBundle\Service\Observer\JimuObserver\DbObserver;

// class SubjectTest extends \PHPUnit_Framework_TestCase 
// {
//     public function testSubject()
//     {
//         $subject = new ConcreteSubject();
//         printf("\nCreate subject correct!\n");
//         $this->assertTrue(isset($subject));
//     }

//     public function testObserver()
//     {
//         $subject = new ConcreteSubject();
//         $obv1 = new DbObserver($subject);
//         $obv2 = new DbObserver($subject);

//         // $subject->notify();
//         printf("\nCreate observer correct!\n");
//         $this->assertTrue(isset($obv1));
//     }
// }