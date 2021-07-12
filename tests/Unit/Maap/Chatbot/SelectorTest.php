<?php
/**
 * User: zhouhua
 * Date: 2021/7/12
 * Time: 4:33 下午
 */

namespace Unit\Maap\Chatbot;

use Easy5G\Factory;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Maap\Structure\Info;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{
    public function testUpdateInfo()
    {
        $config = [
            Const5G::CT => $GLOBALS['config'][Const5G::CT],
            Const5G::CM => $GLOBALS['config'][Const5G::CM]
        ];

        $app = Factory::Maap($config, false);

        $mockData = [
            "errorCode" => 0,
            "errorMessage" => "success"
        ];

        $updateInfo = ['provider' => 'test11'];

        $stub = $this->createMock(HttpClient::class);

        $stub->method('post')->willReturn(json_encode($mockData))->with(
            $this->anything(),
            $this->callback(function ($options) use ($updateInfo) {
                return json_encode($updateInfo) === $options['json'];
            })
        );

        $app->instance('httpClient', $stub);

        $this->assertTrue($app->chatbot->updateInfo(new Info(['provider' => 'test11']), Const5G::CT));

        $this->expectException(InvalidISPException::class);

        $this->assertTrue($app->chatbot->updateInfo(new Info(['provider' => 'test11']), Const5G::CM));
    }

    public function testInfo()
    {
        $config = [
            Const5G::CT => $GLOBALS['config'][Const5G::CT],
            Const5G::CM => $GLOBALS['config'][Const5G::CM]
        ];

        $mockData = '{"accessNo":"10690000","domain":"botplatform.rcs.domain.cn","serviceName":"xxxx","serviceIcon":"https://xxxx/icon.png","TCPage":"https://xxxx.com/","SMSNumber":"10690000","verified":false,"authName":"","authExpires":"","authOrg":"","status":2,"criticalChatbot":false,"url":"https://xxxx.com/","version":2,"provider":"xxxx","category":["education"],"serviceDescription":"","longitude":50.7311865,"latitude":7.0914591,"callBackNumber":"12345678912","themeColour":"#000000","serviceWebsite":"https://xxx.com","emailAddress":"example@test.com","backgroundImage":"https://xxxx/xx.png","address":"","menu":[],"cssStyle":""}';

        $app = Factory::Maap($config, false);

        $stub = $this->createMock(HttpClient::class);

        $stub->method('get')->willReturn($mockData);

        $app->instance('httpClient', $stub);

        $this->assertSame($mockData, $app->chatbot->info(Const5G::CT)->toJson(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));

        $this->expectException(InvalidISPException::class);

        $this->assertTrue($app->chatbot->info(Const5G::CM));
    }
}
