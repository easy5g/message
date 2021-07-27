<?php
/**
 * User: zhouhua
 * Date: 2021/7/23
 * Time: 3:25 下午
 */

namespace Unit\Csp\Customer;

use Easy5G\Factory;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{

    public function testUploadImage()
    {
        $app = Factory::Csp([Const5G::CT => $GLOBALS['csp.config'][Const5G::CT]], false);

        $stub = $this->createMock(HttpClient::class);

        $successRes = '{"code":0,"message":"success","data":{"url":"http://172.17.25.10:9081/7,026feea8f080"}}';

        $stub->method('post')->willReturn($this->returnCallback(function () use ($successRes) {
            return new Response(200, [], $successRes);
        }))->with(
            $this->stringContains('uploadFile'),
            $this->callback(function ($options) use ($app) {
                return $options['headers']['uploadType'] === 0
                    && $options['headers']['authorization'] === $app->access_token->getToken();
            })
        );

        $app->instance('httpClient', $stub);

        $response = $app->customer->uploadImage('./README.md', 0);

        $this->assertInstanceOf(ResponseCollection::class, $response);

        $successResData = json_decode($successRes, true);

        $this->assertSame($successResData['data']['url'], $response->get('url'));
        $this->assertTrue($response->getResult());
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($successResData['message'], $response->getMessage());
    }

    public function testDownload()
    {
        $app = Factory::Csp([Const5G::CT => $GLOBALS['csp.config'][Const5G::CT]], false);

        $stub = $this->createMock(HttpClient::class);

        $content = 'test';

        $media = 'http://172.17.25.10:9081/7,059a5bf1da18';

        $stub->method('post')->willReturn(new Response(200, [], $content))->with(
            $this->stringContains('getFile'),
            $this->callback(function ($options) use ($media) {
                return $options['json'] === ['fileUrl' => $media];
            })
        );

        $app->instance('httpClient', $stub);

        $collect = $app->customer->download($media, 'downloadTest', './');

        $this->assertTrue($collect->getResult());

        $this->assertSame('./downloadTest', $collect->get('file_path'));

        $this->assertFileExists('./downloadTest');

        $this->assertSame($content, file_get_contents('./downloadTest'));

        unlink('./downloadTest');

        $fail = '{"code":40007,"message":"can\'t find the file"}';

        $stub = $this->createMock(HttpClient::class);

        $stub->method('post')->willReturn(new Response(200, [
            'Content-Type' => 'application/json'
        ], $fail))->with(
            $this->stringContains('getFile'),
            $this->callback(function ($options) use ($media) {
                return $options['json'] === ['fileUrl' => $media];
            })
        );

        $app->instance('httpClient', $stub);

        $collect = $app->customer->download($media, 'downloadTest', './');

        $this->assertFalse($collect->getResult());

        $this->assertSame(40007, $collect->getCode());
    }
}
