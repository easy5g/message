<?php
/**
 * User: zhouhua
 * Date: 2021/7/12
 * Time: 5:49 下午
 */

namespace Unit\Maap\Menu;

use Easy5G\Factory;
use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{

    public function testList()
    {
        $config = [
            Const5G::CT => $GLOBALS['config'][Const5G::CT],
            Const5G::CM => $GLOBALS['config'][Const5G::CM]
        ];

        $app = Factory::Maap($config, false);

        $mockData = '{"menu":{"entries":[{"reply":{"displayText":"reply1","postback":{"data":"set_by_chatbot_reply1"}}},{"menu":{"displayText":"SubmenuL1","entries":[{"reply":{"displayText":"reply2","postback":{"data":"set_by_chatbot_reply2"}}},{"action":{"dialerAction":{"dialPhoneNumber":{"phoneNumber":"+8617928222350"}},"displayText":"Call a phone number","postback":{"data":"set_by_chatbot_dial_menu_phone_number"}}}]}}]}}';

        $stub = $this->createMock(HttpClient::class);

        $stub->method('get')->willReturn($mockData);

        $app->instance('httpClient', $stub);

        $this->assertSame(json_encode(json_decode($mockData, true)['menu'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), json_encode($app->menu->list(Const5G::CT), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->expectException(InvalidISPException::class);

        $this->assertTrue($app->menu->list(Const5G::CM));
    }

    public function testCurrent()
    {
        $config = [
            Const5G::CT => $GLOBALS['config'][Const5G::CT],
            Const5G::CM => $GLOBALS['config'][Const5G::CM]
        ];

        $app = Factory::Maap($config, false);

        $mockData = '{"menu":{"entries":[{"reply":{"displayText":"reply1","postback":{"data":"set_by_chatbot_reply1"}}},{"menu":{"displayText":"SubmenuL1","entries":[{"reply":{"displayText":"reply2","postback":{"data":"set_by_chatbot_reply2"}}},{"action":{"dialerAction":{"dialPhoneNumber":{"phoneNumber":"+8617928222350"}},"displayText":"Call a phone number","postback":{"data":"set_by_chatbot_dial_menu_phone_number"}}}]}}]}}';

        $stub = $this->createMock(HttpClient::class);

        $stub->method('get')->willReturn($mockData);

        $app->instance('httpClient', $stub);

        $this->assertSame(json_encode(json_decode($mockData, true)['menu'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), json_encode($app->menu->list(Const5G::CT), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->expectException(InvalidISPException::class);

        $this->assertTrue($app->menu->list(Const5G::CM));
    }

    public function testCreate()
    {
        $config = [
            Const5G::CT => $GLOBALS['config'][Const5G::CT],
            Const5G::CM => $GLOBALS['config'][Const5G::CM]
        ];

        $app = Factory::Maap($config, false);

        $mockData = '{"errorCode": 0,"errorMessage": "success"}';

        $buttons = '{"menu":{"entries":[{"reply":{"displayText":"reply1","postback":{"data":"set_by_chatbot_reply1"}}},{"menu":{"displayText":"SubmenuL1","entries":[{"reply":{"displayText":"reply2","postback":{"data":"set_by_chatbot_reply2"}}},{"action":{"dialerAction":{"dialPhoneNumber":{"phoneNumber":"+8617928222350"}},"displayText":"Call a phone number","postback":{"data":"set_by_chatbot_dial_menu_phone_number"}}}]}}]}}';

        $stub = $this->createMock(HttpClient::class);

        $stub->method('post')->willReturn($mockData);

        $app->instance('httpClient', $stub);

        $this->assertTrue($app->menu->create($buttons,Const5G::CT));

        $this->expectException(InvalidISPException::class);

        $this->assertTrue($app->menu->create($buttons,Const5G::CM));
    }

    public function testCreateErr()
    {
        $config = [
            Const5G::CT => $GLOBALS['config'][Const5G::CT],
        ];

        $app = Factory::Maap($config, false);

        $stub = $this->createMock(HttpClient::class);

        $mockData = '{"errorCode": 0,"errorMessage": "success"}';

        $stub->method('post')->willReturn($mockData);

        $app->instance('httpClient', $stub);

        $this->expectException(BadRequestException::class);

        $app->menu->create('test');
    }
}
