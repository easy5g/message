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

class AppTest extends TestCase
{
    public function testSetDefaultEmptyISP()
    {
        $app = Factory::Chatbot($GLOBALS['chatbot.config'], false);

        $this->expectException(InvalidISPException::class);

        $app->setDefaultISP();
    }

    public function testSetDefaultErrISP()
    {
        $app = Factory::Chatbot([
            Const5G::CU => $GLOBALS['chatbot.config'][Const5G::CU],
            Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT],
        ], false);

        $this->expectException(InvalidISPException::class);

        $app->setDefaultISP(Const5G::CM);
    }

    public function testSetDefaultISP()
    {
        $app = Factory::Chatbot([
            Const5G::CU => $GLOBALS['chatbot.config'][Const5G::CU],
            Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT],
        ], false);

        $app->setDefaultISP(Const5G::CT);

        $this->assertEquals(Const5G::CT, $app->getDefaultISP());

        $app->setDefaultISP(Const5G::CU);

        $this->assertEquals(Const5G::CU, $app->getDefaultISP());
    }

    public function testGetDefaultISP()
    {
        $app = Factory::Chatbot([
            Const5G::CU => $GLOBALS['chatbot.config'][Const5G::CU],
        ], false);

        $this->assertEquals(Const5G::CU, $app->getDefaultISP());
    }
}
