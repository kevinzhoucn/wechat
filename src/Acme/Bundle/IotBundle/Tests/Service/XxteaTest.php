<?php

namespace Tests\Utils;

use Acme\Bundle\IotBundle\Service\XXTEA\Xxtea;

class XxteaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getEncrptStrings
     */
    public function testEncrpt($string, $key, $expected_string)
    {
        $result = Xxtea::encrypt($string, $key);
        $this->assertEquals($expected_string, $result);
    }

    /**
     * @dataProvider getDecrptStrings
     */
    public function testDecrpt($string, $key, $expected_string)
    {
        $result = Xxtea::decrypt($string, $key);
        $this->assertEquals($expected_string, $result);
    }

    public function getEncrptStrings()
    {
        return array(
                    array('userid=1897654789&channel_id=2&value=23456',
                          'TTuuIvb76TY123Ki',
                          '595c09e211b549902a09cec2e1fe7bfdc6d2ca285c3e531295d1efba1040c5ef574bca135ff603115b8c089f')
        );
    }

    public function getDecrptStrings()
    {
        return array(
                    array('504132059c1e375f32fed77fe0c622fa56a438ee65da43568ce222081e23ced4f14084a91dd0b925',
                          'TTuuIvb76TY123Ki',
                          'userid=189765669&channel_id=2&value=23')
        );
    }
}
