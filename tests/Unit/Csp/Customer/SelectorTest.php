<?php
/**
 * User: zhouhua
 * Date: 2021/7/23
 * Time: 3:25 下午
 */

namespace Unit\Csp\Customer;

use Easy5G\Csp\Customer\Selector;
use Easy5G\Factory;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;

class SelectorTest extends TestCase
{

    public function testUploadImage()
    {
//        $app = Factory::Csp([Const5G::CT => $GLOBALS['csp.config'][Const5G::CT]]);
//
//        $stub = $this->createMock(HttpClient::class);
//
//        $stub->method('post')->willReturn($this->returnCallback(function ($path, $option) use ($failRes, $successRes) {
//            if ($option['multipart'][0]['filename'] === 'README.md') {
//                return $failRes;
//            } else {
//                return $successRes;
//            }
//        }))->with(
//            $this->stringContains('upload'),
//            $this->callback(function ($options) use ($app) {
//                return $options['headers']['UploadMode'] === 'perm'
//                    && $options['headers']['Authorization'] === $app->access_token->getToken()
//            })
//        );
//
//        $app->instance('httpClient', $stub);
//
//        $app->customer->uploadImage('./README.md',0);
    }
}
