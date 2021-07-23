<?php
/**
 * User: zhouhua
 * Date: 2021/7/2
 * Time: 2:15 下午
 */

namespace Unit\Chatbot;

use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Chatbot\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public static $config;

    public static function setUpBeforeClass(): void
    {
        self::$config = new Config([
            Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT],
            Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM],
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        self::$config = null;
    }

    public function testSet()
    {
        self::$config->set('test', 111);

        $this->assertSame(111, self::$config->get('test'));
    }

    public function testSetErr()
    {
        $this->expectException(InvalidConfigException::class);

        self::$config->set(Const5G::CU, 111);
    }
}
