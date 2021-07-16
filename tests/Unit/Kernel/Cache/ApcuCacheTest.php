<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 9:41 上午
 */

namespace Unit\Kernel\Cache;


use Easy5G\Factory;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ApcuAdapter;

/**
 * @requires extension apcu
 */
class ApcuCacheTest extends TestCase
{
    protected static $cache;

    public static function setUpBeforeClass(): void
    {
        $config = [Const5G::CU => $GLOBALS['config'][Const5G::CU]];

        $config['cache'] = [
            'default' => 'dev',
            'dev' => [
                'driver' => 'apcu',
            ]
        ];

        $app = Factory::Chatbot($config,false);

        self::$cache = $app->cache;
    }

    public static function tearDownAfterClass(): void
    {
        self::$cache = null;
    }

    public function testClass()
    {
        $this->assertInstanceOf(ApcuAdapter::class,self::$cache->driver());
    }

    public function testSet()
    {
        self::$cache->set('test', 1);

        $this->assertEquals(1, self::$cache->get('test'));

        self::$cache->set('test', '2');

        $this->assertEquals('2', self::$cache->get('test'));

        self::$cache->set('test', 3, 1);

        sleep(1);

        $this->assertEquals(null, self::$cache->get('test'));

        self::$cache->set('test', 4, 1);

        self::$cache->set('test', 5);

        $this->assertEquals(5, self::$cache->get('test'));
    }

    public function testGet()
    {
        $this->assertEquals(5, self::$cache->get('test'));
    }

    public function testDelete()
    {
        self::$cache->del('test');

        $this->assertEquals(null, self::$cache->get('test'));
    }
}
