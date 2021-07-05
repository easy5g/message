<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 2:44 下午
 */

namespace Unit\Kernel;


use Easy5G\Factory;
use Easy5G\Kernel\Config\Repository;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;

class ISPSelectorTest extends TestCase
{
    public function testSetDefaultEmptyISP()
    {
        $selector = Factory::Maap($GLOBALS['config'], false)->base;

        $this->expectException(InvalidISPException::class);

        $selector->setDefaultISP();
    }

    public function testSetDefaultErrISP()
    {
        $selector = Factory::Maap([
            Const5G::CU => $GLOBALS['config'][Const5G::CU],
            Const5G::CT => $GLOBALS['config'][Const5G::CT],
        ], false)->base;

        $this->expectException(InvalidISPException::class);

        $selector->setDefaultISP(Const5G::CM);
    }

    public function testSetDefaultISP()
    {
        $selector = Factory::Maap([
            Const5G::CU => $GLOBALS['config'][Const5G::CU],
            Const5G::CT => $GLOBALS['config'][Const5G::CT],
        ], false)->base;

        $selector->setDefaultISP(Const5G::CT);

        $this->assertEquals(Const5G::CT, $selector->getDefaultISP());

        $selector->setDefaultISP(Const5G::CU);

        $this->assertEquals(Const5G::CU, $selector->getDefaultISP());
    }

    public function testGetDefaultISP()
    {
        $selector = Factory::Maap([
            Const5G::CU => $GLOBALS['config'][Const5G::CU],
        ], false)->base;

        $this->assertEquals(Const5G::CU, $selector->getDefaultISP());
    }
}
