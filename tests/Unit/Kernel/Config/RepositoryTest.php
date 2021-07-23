<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 1:57 下午
 */

namespace Unit\Kernel\Config;


use Easy5G\Kernel\Config\Repository;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    public static $config;

    public static function setUpBeforeClass(): void
    {
        $config = $GLOBALS['chatbot.config'];

        self::$config = new Repository([
            Const5G::CM => $config[Const5G::CM]
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        self::$config = null;
    }

    /**
     * @covers \Easy5G\Kernel\Config\Repository::set
     * @covers \Easy5G\Kernel\Config\Repository::get
     */
    public function testSetGet()
    {
        $this->assertSame($GLOBALS['chatbot.config'][Const5G::CM]['appId'], self::$config->get(Const5G::CM . '.appId'));

        $this->assertNull(self::$config->get('test.test'));
        $this->assertSame('testDefault', self::$config->get(Const5G::CU, 'testDefault'));

        self::$config->set(Const5G::CU, [
            'appId' => 'testAppId2'
        ]);

        self::$config->set(Const5G::CU . '.appKey', 'testAppKey2');

        $this->assertSame([
            'appId' => 'testAppId2',
            'appKey' => 'testAppKey2',
        ], self::$config->get(Const5G::CU));
    }

    public function testHas()
    {
        $this->assertTrue(self::$config->has(Const5G::CU . '.appId'));
        $this->assertFalse(self::$config->has(Const5G::CU . '.appSecret'));

        self::$config->set('test', null);

        $this->assertTrue(self::$config->has('test'));
    }

    public function testGetServiceProvidersNum()
    {
        $this->assertEquals(2, self::$config->getServiceProvidersNum());
    }

    public function testGetServiceProviders()
    {
        $this->assertSame([Const5G::CM => Const5G::CM, Const5G::CU => Const5G::CU], self::$config->getServiceProviders());
    }
}
