<?php
/**
 * User: zhouhua
 * Date: 2021/7/12
 * Time: 4:33 下午
 */

namespace Unit\Chatbot\Info;

use Easy5G\Factory;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Chatbot\Structure\Info;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{
    public function testUpdate()
    {
        $config = [
            Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT],
            Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM]
        ];

        $app = Factory::Chatbot($config, false);

        $mockData = [
            "errorCode" => 0,
            "errorMessage" => "success"
        ];

        $updateInfo = ['provider' => 'test11'];

        $stub = $this->createMock(HttpClient::class);

        $stub->method('post')->willReturn(json_encode($mockData))->with(
            $this->stringContains('optionals'),
            $this->callback(function ($options) use ($updateInfo) {
                return json_encode($updateInfo) === $options['json'];
            })
        );

        $app->instance('httpClient', $stub);

        $this->assertSame(json_encode($mockData), $app->info->update($app->chatbotInfoFactory->create(['provider' => 'test11'], Const5G::CT)));

        $this->expectException(InvalidISPException::class);

        $app->info->update($app->chatbotInfoFactory->create(['provider' => 'test11']), Const5G::CM);
    }

    public function testAll()
    {
        $config = [
            Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT],
            Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM]
        ];

        $mockData = '{"accessNo":"10690000","domain":"botplatform.rcs.domain.cn","serviceName":"xxxx","serviceIcon":"https://xxxx/icon.png","TCPage":"https://xxxx.com/","SMSNumber":"10690000","verified":false,"authName":"","authExpires":"","authOrg":"","status":2,"criticalChatbot":false,"url":"https://xxxx.com/","version":2,"provider":"xxxx","category":["education"],"serviceDescription":"","longitude":50.7311865,"latitude":7.0914591,"callBackNumber":"12345678912","themeColour":"#000000","serviceWebsite":"https://xxx.com","emailAddress":"example@test.com","backgroundImage":"https://xxxx/xx.png","address":"","menu":[],"cssStyle":""}';

        $app = Factory::Chatbot($config, false);

        $stub = $this->createMock(HttpClient::class);

        $stub->method('get')->willReturn($mockData);

        $app->instance('httpClient', $stub);

        $this->assertSame($mockData, $app->info->all(Const5G::CT)->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->expectException(InvalidISPException::class);

        $this->assertTrue($app->info->all(Const5G::CM));
    }
}
