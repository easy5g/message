<?php
/**
 * User: zhouhua
 * Date: 2021/7/9
 * Time: 11:43 上午
 */

namespace Unit\Maap\Structure;

use Easy5G\Maap\Structure\Info;
use PHPUnit\Framework\TestCase;

class InfoTest extends TestCase
{
    public static $info;
    public static $extraLongString;

    public static function setUpBeforeClass(): void
    {
        self::$info = new Info();
        self::$extraLongString = str_pad('',1000,'1');
    }

    public static function tearDownAfterClass(): void
    {
        self::$info = null;
    }

    public function test__construct()
    {
        $info = new Info([
            'address' =>'test',
            'test' =>1,
        ]);

        $this->assertSame(['address' =>'test'],$info->all());
    }

    public function testArrayAccess()
    {
        self::$info['test'] = true;

        $this->assertNull(self::$info['test']);

        $this->assertNull(self::$info->address);

        $this->assertNull(self::$info->get('address'));

        self::$info['address'] = 'test';

        $this->assertEquals('test',self::$info['address']);

        $this->assertEquals('test',self::$info->address);

        $this->assertEquals('test',self::$info->get('address'));

        self::$info->setAddress('111');

        $this->assertEquals('111',self::$info->address);
    }

    public function testErrSet()
    {
        $this->assertFalse(self::$info->setCategory(12));

        $this->assertFalse(self::$info->setCategory(self::$extraLongString));

        $this->assertFalse(self::$info->setLongitude('123'));

        $this->assertFalse(self::$info->setLatitude('123'));
    }

    public function testSetCategory()
    {
        self::$info->setCategory('test');

        $this->assertEquals(['test'],self::$info->category);
    }

    public function testSetLongitude()
    {
        self::$info->setLongitude(1.2);

        $this->assertEquals(1.2,self::$info->longitude);
    }

    public function testSetCallBackNumber()
    {
        self::$info->setCallBackNumber('17541');

        $this->assertEquals('17541',self::$info->callBackNumber);
    }

    public function testSetLatitude()
    {
        self::$info->setLatitude(1.3);

        $this->assertEquals(1.3,self::$info->latitude);
    }

    public function testSetThemeColour()
    {
        self::$info->setThemeColour('#002244');

        $this->assertEquals('#002244',self::$info->themeColour);
    }

    public function testSetEmailAddress()
    {
        self::$info->setEmailAddress('22222@qq.com');

        $this->assertEquals('22222@qq.com',self::$info->emailAddress);
    }

    public function testSetAddress()
    {
        self::$info->setAddress('china');

        $this->assertEquals('china',self::$info->address);
    }

    public function testSetServiceWebsite()
    {
        self::$info->setServiceWebsite('this is xpz');

        $this->assertEquals('this is xpz',self::$info->serviceWebsite);
    }

    public function testSetServiceDescription()
    {
        self::$info->setServiceDescription('this is xpz');

        $this->assertEquals('this is xpz',self::$info->serviceDescription);
    }

    public function testSetCssStyle()
    {
        self::$info->setCssStyle('https://wowlian.cn/');

        $this->assertEquals('https://wowlian.cn/',self::$info->cssStyle);
    }

    public function testSetBackgroundImage()
    {
        self::$info->setBackgroundImage('https://wowlian.cn/');

        $this->assertEquals('https://wowlian.cn/',self::$info->backgroundImage);
    }

    public function testSetProvider()
    {
        self::$info->setProvider('wow lian chatbot');

        $this->assertEquals('wow lian chatbot',self::$info->provider);
    }
}
