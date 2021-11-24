<?php
/**
 * User: zhouhua
 * Date: 2021/7/2
 * Time: 9:05 上午
 */

namespace Unit\Csp\Auth;

use Easy5G\Factory;
use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{
    public function testGetTokenErr()
    {
        $this->expectException(BadRequestException::class);

        $config['csp'] = [Const5G::CT => $GLOBALS['config']['csp'][Const5G::CT]];

        Factory::Csp($config, false)->access_token->getToken(true, null, 'http://127.0.0.1:9999/test');
    }

    public function testGetToken()
    {
        $config = [
            'csp' => [
                Const5G::CT => $GLOBALS['config']['csp'][Const5G::CT],
                Const5G::CM => $GLOBALS['config']['csp'][Const5G::CM]
            ]
        ];

        $app = Factory::Csp($config, false);

        $mockData = [
            "code" => 0,
            "message" => 'success',
            "data" => [
                'accessToken' => 'GciOiJIUzI1NiJ9.eyJ'
            ],
        ];

        $stub = $this->createMock(HttpClient::class);

        $stub->method('post')->willReturn(json_encode($mockData));

        $app->instance('httpClient', $stub);

        $this->assertSame($mockData['data']['accessToken'], $app->access_token->getToken(true, Const5G::CT));

        $exceptAccessToken = 'Basic ' . base64_encode($config['csp'][Const5G::CM]['cspid'] . ':' . hash('sha256', $config['csp'][Const5G::CM]['cspToken'] . gmdate('D, d M Y H:i:s', time()) . ' GMT'));

        $this->assertSame($exceptAccessToken, $app->access_token->getToken(true, Const5G::CM));
    }
}
