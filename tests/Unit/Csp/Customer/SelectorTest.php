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
        $app = Factory::Csp([Const5G::CT => $GLOBALS['csp.config'][Const5G::CT]],false);

        $stub = $this->createMock(HttpClient::class);

        $successRes = '{"code":0,"message":"success","data":{"url":"http://172.17.25.10:9081/7,026feea8f080"}}';

        $stub->method('post')->willReturn($this->returnCallback(function () use ($successRes) {
            return new Response(200,[],$successRes);
        }))->with(
            $this->stringContains('uploadFile'),
            $this->callback(function ($options) use ($app) {
                return $options['headers']['uploadType'] === 0
                    && $options['headers']['authorization'] === $app->access_token->getToken();
            })
        );

        $app->instance('httpClient', $stub);

        $response = $app->customer->uploadImage('./README.md',0);

        $this->assertInstanceOf(ResponseCollection::class,$response);

        $successResData = json_decode($successRes,true);

        $this->assertSame($successResData['data']['url'],$response->get('url'));
        $this->assertTrue($response->getResult());
        $this->assertSame(200,$response->getStatusCode());
        $this->assertSame($successResData['message'],$response->getMessage());
    }

//    public function testDownload()
//    {
//        $app = Factory::Csp([Const5G::CT => $GLOBALS['csp.config'][Const5G::CT]],false);
//
//        $stub = $this->createMock(HttpClient::class);
//
//        $stub->method('post')->willReturn($this->returnCallback(function () use ($successRes) {
//            return new Response(200,[],$successRes);
//        }))->with(
//            $this->stringContains('uploadFile'),
//            $this->callback(function ($options) use ($app) {
//                return $options['headers']['uploadType'] === 0
//                    && $options['headers']['authorization'] === $app->access_token->getToken();
//            })
//        );
//
//        $app->instance('httpClient', $stub);
//
//        $response = $app->customer->uploadImage('./README.md',0);
//
//        $this->assertInstanceOf(ResponseCollection::class,$response);
//
//        $successResData = json_decode($successRes,true);
//
//        $this->assertSame($successResData['data']['url'],$response->get('url'));
//        $this->assertSame($successResData['code'],$response->getCode());
//        $this->assertSame(200,$response->getStatusCode());
//        $this->assertSame($successResData['message'],$response->getMessage());
//    }
}
