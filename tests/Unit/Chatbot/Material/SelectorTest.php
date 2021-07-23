<?php
/**
 * User: zhouhua
 * Date: 2021/7/16
 * Time: 2:49 下午
 */

namespace Unit\Chatbot\Material;

use Easy5G\Chatbot\Material\Selector;
use Easy5G\Factory;
use Easy5G\Kernel\Exceptions\CommonException;
use Easy5G\Kernel\HttpClient;
use Easy5G\Kernel\Support\Const5G;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SelectorTest extends TestCase
{
    public function testDownload()
    {
        $ct = Factory::Chatbot([Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT]], false);

        $stubCt = $this->createMock(HttpClient::class);

        $content = 'test';

        $media = 'http://127.0.0.1:8355/README.md';

        $stubCt->method('get')->willReturn(new Response(200, [], $content))->with(
            $this->stringContains('download'),
            $this->callback(function ($options) use ($media) {
                return $options['headers']['url'] === $media;
            })
        );

        $ct->instance('httpClient', $stubCt);

        $this->assertTrue($ct->material->download($media, 'downloadTest', './'));

        $this->assertTrue(is_file('./downloadTest'));

        $this->assertSame($content, file_get_contents('./downloadTest'));

        unlink('./downloadTest');

        $fail = '{"errorCode":40007,"errorMessage":"can\'t find the file"}';

        $stubCt = $this->createMock(HttpClient::class);

        $stubCt->method('get')->willReturn(new Response(200, [
            'Content-Type' => 'application/json'
        ], $fail))->with(
            $this->stringContains('download'),
            $this->callback(function ($options) use ($media) {
                return $options['headers']['url'] === $media;
            })
        );

        $ct->instance('httpClient', $stubCt);

        $this->assertSame($fail, $ct->material->download($media, 'downloadTest', './'));

        $cm = Factory::Chatbot([Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM]], false);

        $stubCm = $this->createMock(HttpClient::class);

        $stubCm->method('post')->willReturn(new Response(200, [], $content))->with(
            $this->stringContains('README.md'),
            $this->callback(function ($options) {
                return $options['headers']['User-Agent'] === $GLOBALS['chatbot.config'][Const5G::CM]['chatbotURI'];
            })
        );

        $cm->instance('httpClient', $stubCm);

        $this->assertTrue($cm->material->download($media, 'downloadTest', './'));

        $this->assertTrue(is_file('./downloadTest'));

        $this->assertSame($content, file_get_contents('./downloadTest'));

        unlink('./downloadTest');

        $stubCm = $this->createMock(HttpClient::class);

        $stubCm->method('post')->willReturn(new Response(404, [], ''))->with(
            $this->stringContains('README.md'),
            $this->callback(function ($options) {
                return $options['headers']['User-Agent'] === $GLOBALS['chatbot.config'][Const5G::CM]['chatbotURI'];
            })
        );

        $cm->instance('httpClient', $stubCm);

        $this->assertSame(404, $cm->material->download($media, 'downloadTest', './'));
    }

    public function testDelete()
    {
        $ct = Factory::Chatbot([Const5G::CT => $GLOBALS['chatbot.config'][Const5G::CT]], false);

        $stubCt = $this->createMock(HttpClient::class);

        $successRes = '{"deleteMode":"perm","fileCount":20,"totalCount":100,"errorCode":0,"errorMessage":"success"}';

        $media = 'http://127.0.0.1:8355/README.md';

        $stubCt->method('delete')->willReturn($successRes)->with(
            $this->stringContains('delete'),
            $this->callback(function ($options) use ($media) {
                return $options['headers']['url'] === $media;
            })
        );

        $ct->instance('httpClient', $stubCt);

        $this->assertSame($successRes, $ct->material->delete($media));

        $cm = Factory::Chatbot([Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM]], false);

        $stuCm = $this->createMock(HttpClient::class);

        $successRes = '{"msg":"success","code":"0"}';

        $stuCm->method('delete')->willReturn($successRes)->with(
            $this->anything(),
            $this->callback(function ($options) use ($media) {
                return $options['headers']['tid'] === $media &&
                    $options['headers']['User-Agent'] === $GLOBALS['chatbot.config'][Const5G::CM]['chatbotURI'];
            })
        );

        $cm->instance('httpClient', $stuCm);

        $this->assertSame($successRes, $cm->material->delete($media));
    }

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
                return $options['headers']['UploadMode'] === 'perm'
                    && $options['headers']['Authorization'] === $ct->access_token->getToken()
                    && $options['multipart']['0']['name'] === '1';
            })
        );

        $ct->instance('httpClient', $stub);

        $this->assertSame($failRes, $ct->material->upload('./README.md'));

        $this->assertSame($successRes, $ct->material->upload('./LICENSE'));

        $cm = Factory::Chatbot([Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM]], false);

        $stub = $this->createMock(HttpClient::class);

        $successRes = '{"msg":"success","code":"0","tid":"565397473954299904"}';

        $failRes = '{"msg":"chatbotId 错误","code":"10005"}';

        $stub->method('post')->willReturn($this->returnCallback(function ($path, $option) use ($failRes, $successRes) {
            if ($option['multipart'][0]['filename'] === 'README.md') {
                return $failRes;
            } else {
                return $successRes;
            }
        }))->with(
            $this->anything(),
            $this->callback(function ($options) use ($cm) {
                return $options['headers']['User-Agent'] === $GLOBALS['chatbot.config'][Const5G::CM]['chatbotURI']
                    && $options['headers']['Authorization'] === $cm->access_token->getToken()
                    && $options['multipart']['0']['name'] === 'File';
            })
        );

        $cm->instance('httpClient', $stub);

        $this->assertSame($failRes, $cm->material->upload('./README.md'));

        $this->assertSame($successRes, $cm->material->upload('./LICENSE'));

        $this->expectException(CommonException::class);

        $ct->material->upload('test');
    }

    public function testNotify()
    {
        $cm = Factory::Chatbot([Const5G::CM => $GLOBALS['chatbot.config'][Const5G::CM]], false);

        $cmXml = '<?xml version="1.0" encoding="UTF-8"?>
<file xmlns="urn:gsma:params:xml:ns:rcs:rcs:fthttp" xmlns:e="urn:gsma:params :xml:ns:rcs:rcs:up:fthttpext">
    <file-info type="file">
        <file-size>185721</file-size>
        <file-name>69828977037225984.jpg</file-name>
        <content-type>image/jpeg</content-type>
        <data url="https://ftcontentserv.XXXX.3gtwork.org:2229/s/06292023461311490080503901FD.jpg" until="2021-07-02T20:23:46Z"/>
        <file-exist>1</file-exist>
        <e:branded-url>https://1/s/YMReXuAJrgs.jpg</e:branded-url>
    </file-info>
</file>';

        $request = new Request(['chatbotURI' => 'xx'], [], [], [], [], [
            'HTTP_Authstatus' => 0,
        ], $cmXml);

        $cm->instance('request', $request);

        $response = $cm->material->notify(function ($receiveData) {
            $this->assertSame('69828977037225984.jpg', $receiveData['file-info']['file-name']);
        });

        $this->assertSame(200,$response->getStatusCode());
    }
}
