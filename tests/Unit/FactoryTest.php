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

        Factory::Maap([]);
    }

    public function testMakeErrException()
    {
        $this->expectException(InvalidConfigException::class);

        Factory::Maap(['test' => 1]);
    }

    public function testMake()
    {
        $maap1 = Factory::Maap([Const5G::CM => $GLOBALS['config'][Const5G::CM]]);

        $maap2 = Factory::Maap();

        $this->assertSame($maap1, $maap2);

        $maap3 = Factory::Maap([Const5G::CT => $GLOBALS['config'][Const5G::CT]]);

        $this->assertSame($maap3, $maap2);

        $this->assertEquals($GLOBALS['config'][Const5G::CT]['appId'], $maap3->config->get(Const5G::CT)['appId']);

        $maap4 = Factory::Maap([Const5G::CT => $GLOBALS['config'][Const5G::CT]], false);

        $this->assertNotSame($maap4, $maap3);

        $maap5 = Factory::Maap([Const5G::CU => $GLOBALS['config'][Const5G::CU]], false);

        $this->assertNotSame($maap5, $maap4);

        $this->assertEquals($GLOBALS['config'][Const5G::CT]['appId'], $maap4->config->get(Const5G::CT)['appId']);

        $this->assertEquals($GLOBALS['config'][Const5G::CU]['appId'], $maap5->config->get(Const5G::CU)['appId']);
    }
}
