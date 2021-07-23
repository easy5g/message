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
        $Chatbot1 = Factory::Chatbot([Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM]]);

        $Chatbot2 = Factory::Chatbot();

        $this->assertSame($Chatbot1, $Chatbot2);

        $Chatbot3 = Factory::Chatbot([Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT]]);

        $this->assertSame($Chatbot3, $Chatbot2);

        $this->assertEquals($GLOBALS['chatbot.config'][Const5G::CT]['appId'], $Chatbot3->config->get(Const5G::CT)['appId']);

        $Chatbot4 = Factory::Chatbot([Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT]], false);

        $this->assertNotSame($Chatbot4, $Chatbot3);

        $Chatbot5 = Factory::Chatbot([Const5G::CU => $GLOBALS['chatbot.config'][Const5G::CU]], false);

        $this->assertNotSame($Chatbot5, $Chatbot4);

        $this->assertEquals($GLOBALS['chatbot.config'][Const5G::CT]['appId'], $Chatbot4->config->get(Const5G::CT)['appId']);

        $this->assertEquals($GLOBALS['chatbot.config'][Const5G::CU]['appId'], $Chatbot5->config->get(Const5G::CU)['appId']);
    }
}
