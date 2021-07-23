<?php
/**
 * User: zhouhua
 * Date: 2021/7/2
 * Time: 9:05 上午
 */

namespace Unit\Chatbot\Auth;

use Easy5G\Factory;
use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class SelectorTest extends TestCase
{
    public function testGetTokenErr()
    {
        $this->expectException(BadRequestException::class);

        $config = [Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT]];

        Factory::Chatbot($config, false)->access_token->getToken(true, null, 'http://127.0.0.1:9999/test');
    }

    public function testGetToken()
    {
        $config = [
            Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT],
            Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM]
        ];

        $app = Factory::Chatbot($config, false);

        $mockData = [
            "accessToken" => "xxxxxxxx",
            "expires" => 7200,
            "errorCode" => 0,
            "url" => "xxxxxx"
        ];

        $stub = $this->createMock(HttpClient::class);

        $stub->method('post')->willReturn(json_encode($mockData));

        $app->instance('httpClient', $stub);

        $this->assertSame($mockData['accessToken'], $app->access_token->getToken(true, Const5G::CT));

        $exceptAccessToken = 'Basic ' . base64_encode($config[Const5G::CM]['appid'] . ':' . hash('sha256', hash('sha256', $config[Const5G::CM]['password']) . gmdate('D, d M Y H:i:s', time()) . ' GMT'));

        $this->assertSame($exceptAccessToken, $app->access_token->getToken(true, Const5G::CM));
    }

    public function testNotify()
    {
        $config = [
            Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT],
        ];

        $app = Factory::Chatbot($config, false);

        $queryData = [
            'token' => $app->access_token->getToken(),
            'timestamp' => time(),
            'nonce' => uniqid(),
        ];

        $_SERVER['HTTP_timestamp'] = $queryData['timestamp'];
        $_SERVER['HTTP_nonce'] = $queryData['nonce'];
        $_SERVER['HTTP_echoStr'] = 'test';

        sort($queryData, SORT_STRING);

        $_SERVER['HTTP_signature'] = hash('sha256', implode('', $queryData));

        $response = $app->access_token->notify();

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame($_SERVER['HTTP_echoStr'], $response->headers->get('echoStr'));

        $response = $app->access_token->notify(function () {
            return false;
        });

        $this->assertInstanceOf(Response::class, $response);

        $this->assertSame('', $response->headers->get('echoStr'));
    }
}
