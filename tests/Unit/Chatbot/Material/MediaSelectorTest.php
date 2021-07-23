<?php
/**
 * User: zhouhua
 * Date: 2021/7/16
 * Time: 2:49 下午
 */

namespace Unit\Chatbot\Material;


use Easy5G\Factory;
use Easy5G\Kernel\Exceptions\CommonException;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use PHPUnit\Framework\TestCase;

class MediaSelectorTest extends TestCase
{
    public function testUpload()
    {
        $ct = Factory::Chatbot([Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT]], false);

        $stub = $this->createMock(HttpClient::class);

        $failRes = '{"errorCode":40005,"errorMessage":"invalid media type"}';

        $successRes = '{"fileInfo":[{"url":http://124.127.121.100/temp/src/2020062217asdfkjaoskd/836ee/view/37,3c3504f6e4cc6c5274f0.jpg","fileName":"AA.jpg","contentType":"image/jpg","fileSize":22347,"until":"2017-04-25T12:17:07Z"}],"fileCount":100,"totalCount":300,"errorCode":0}';

        $stub->method('post')->willReturn($this->returnCallback(function ($path, $option) use ($failRes, $successRes) {
            if ($option['multipart'][0]['filename'] === 'README.md') {
                return $failRes;
            } else {
                return $successRes;
            }
        }))->with(
            $this->stringContains('upload'),
            $this->callback(function ($options) use ($ct) {
                return $options['headers']['UploadMode'] === 'temp'
                    && $options['headers']['Authorization'] === $ct->access_token->getToken()
                    && $options['multipart']['0']['name'] === '1';
            })
        );

        $ct->instance('httpClient', $stub);

        $this->assertSame($failRes, $ct->media->upload('./README.md'));

        $this->assertSame($successRes, $ct->media->upload('./LICENSE'));

        $this->expectException(CommonException::class);

        $ct->material->upload('test');
    }
}
