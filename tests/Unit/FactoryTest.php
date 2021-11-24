<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 6:07 下午
 */

namespace Unit;

use Easy5G\Factory;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testMakeEmptyException()
    {
        $this->expectException(InvalidConfigException::class);

        Factory::Chatbot([],false);
    }

    public function testMakeErrException()
    {
        $this->expectException(InvalidConfigException::class);

        Factory::Chatbot(['test' => 1]);
    }

    public function testMake()
    {
        $config['chatbot'] = [Const5G::CM => $GLOBALS['config']['chatbot'][Const5G::CM]];

        $Chatbot1 = Factory::Chatbot($config);

        $Chatbot2 = Factory::Chatbot();

        $this->assertSame($Chatbot1, $Chatbot2);

        $config['chatbot'] = [Const5G::CT => $GLOBALS['config']['chatbot'][Const5G::CT]];

        $Chatbot3 = Factory::Chatbot($config);

        $this->assertSame($Chatbot3, $Chatbot2);

        $this->assertEquals($GLOBALS['config']['chatbot'][Const5G::CT]['appId'], $Chatbot3->config->get(Const5G::CT)['appId']);

        $Chatbot4 = Factory::Chatbot($config, false);

        $this->assertNotSame($Chatbot4, $Chatbot3);

        $config['chatbot'] = [Const5G::CU => $GLOBALS['config']['chatbot'][Const5G::CU]];

        $Chatbot5 = Factory::Chatbot($config, false);

        $this->assertNotSame($Chatbot5, $Chatbot4);

        $this->assertEquals($GLOBALS['config']['chatbot'][Const5G::CT]['appId'], $Chatbot4->config->get(Const5G::CT)['appId']);

        $this->assertEquals($GLOBALS['config']['chatbot'][Const5G::CU]['appId'], $Chatbot5->config->get(Const5G::CU)['appId']);
    }
}
