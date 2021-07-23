<?php
/**
 * User: zhouhua
 * Date: 2021/7/22
 * Time: 4:31 下午
 */

namespace Unit\Chatbot\Structure;

use Easy5G\Chatbot\Structure\Menu;
use Easy5G\Factory;
use Easy5G\Kernel\Factory\ChatbotMenuFactory;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    public function testAddMenu()
    {
        $menu = '{"menu":{"entries":[{"reply":{"displayText":"reply1","postback":{"data":"set_by_chatbot_reply1"}}},{"menu":{"displayText":"SubmenuL1","entries":[{"reply":{"displayText":"reply2","postback":{"data":"set_by_chatbot_reply2"}}},{"action":{"dialerAction":{"dialPhoneNumber":{"phoneNumber":"+8617928222350"}},"displayText":"Call a phone number","postback":{"data":"set_by_chatbot_dial_menu_phone_number"}}}]}}]}}';

        $app = Factory::Chatbot([Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT]]);

        $this->assertSame($menu, $app->chatbotMenuFactory->create($menu)->toJson());

    }

    public function testAddButton()
    {
        $button1 = '{"reply":{"displayText":"reply1","postback":{"data":"set_by_chatbot_reply1"}}}';
        $button2 = '{"reply":{"displayText":"reply2","postback":{"data":"set_by_chatbot_reply2"}}}';
        $button3 = '{"action":{"dialerAction":{"dialPhoneNumber":{"phoneNumber":"+8617928222350"}},"displayText":"Call a phone number","postback":{"data":"set_by_chatbot_dial_menu_phone_number"}}}';
        $display = 'SubmenuL1';

        $menu = '{"menu":{"entries":[' . $button1 . ',{"menu":{"displayText":"' . $display . '","entries":[' . $button2 . ',' . $button3 . ']}}]}}';

        $app = Factory::Chatbot([Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT]]);

        /** @var Menu $menuInstance */
        $menuInstance = $app->chatbotMenuFactory->create();

        $menuInstance->addButton($app->chatbotButtonFactory->create(json_decode($button1, true)));
        $secondMenu = $menuInstance->addMenu($display);
        $secondMenu->addButton($app->chatbotButtonFactory->create(json_decode($button2, true)));
        $secondMenu->addButton($app->chatbotButtonFactory->create(json_decode($button3, true)));

        $this->assertSame($menu, $menuInstance->toJson());
    }
}
