<?php
/**
 * User: zhouhua
 * Date: 2021/8/6
 * Time: 4:12 下午
 */

namespace Unit\Chatbot\Structure\Message;

use Easy5G\Chatbot\Structure\Message\File;
use Easy5G\Kernel\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public static $fileInfo;

    public static function setUpBeforeClass(): void
    {
        self::$fileInfo = [
            [
                'type' => 'thumbnail',
                'fileName' => 'test1',
                'contentType' => 'image/jpg',
                'fileSize' => 7427,
                'until' => '2019-04-25T12:17:07Z',
                'url' => 'http://127.0.0.1:8080/test'
            ],
            [
                'type' => 'file',
                'fileName' => 'test2',
                'contentType' => 'image/jpg',
                'fileSize' => 2012,
                'until' => '2019-04-25T12:17:07Z',
                'url' => 'http://127.0.0.1:8080/test'
            ]
        ];
    }

    public static function tearDownAfterClass(): void
    {
        self::$fileInfo = null;
    }

    public function testFileException()
    {
        $fileInfo = self::$fileInfo;

        new File([$fileInfo[1]]);

        try {
            new File([$fileInfo[0]]);
        } catch (InvalidArgumentException $e) {

        }

        $this->assertInstanceOf(InvalidArgumentException::class, $e);

        try {
            new File([$fileInfo[1], $fileInfo[1]]);
        } catch (InvalidArgumentException $e1) {

        }

        $this->assertInstanceOf(InvalidArgumentException::class, $e1);

        try {
            new File($fileInfo[0]);
        } catch (InvalidArgumentException $e2) {

        }

        $this->assertInstanceOf(InvalidArgumentException::class, $e2);

        try {
            unset($fileInfo[0]['url']);

            new File($fileInfo[0]);
        } catch (InvalidArgumentException $e2) {

        }

        $this->assertInstanceOf(InvalidArgumentException::class, $e2);
    }

    public function testSetFileUrl()
    {
        $file = new File();

        $file->setFileUrl(self::$fileInfo[1]['url']);

        $this->assertSame(self::$fileInfo[1]['url'], $file->getUTText()[0]['contentText'][0]['url']);
    }

    public function testSetFileSize()
    {
        $file = new File();

        $file->setFileSize(self::$fileInfo[1]['fileSize']);

        $this->assertSame(self::$fileInfo[1]['fileSize'], $file->getUTText()[0]['contentText'][0]['fileSize']);
    }

    public function testSetFileContentType()
    {
        $file = new File();

        $file->setFileContentType(self::$fileInfo[1]['contentType']);

        $this->assertSame(self::$fileInfo[1]['contentType'], $file->getUTText()[0]['contentText'][0]['contentType']);
    }

    public function testSetFileUntil()
    {
        $file = new File();

        $file->setFileUntil(self::$fileInfo[1]['until']);

        $this->assertSame(self::$fileInfo[1]['until'], $file->getUTText()[0]['contentText'][0]['until']);
    }

    public function testSetFilename()
    {
        $file = new File();

        $file->setFilename(self::$fileInfo[1]['fileName']);

        $this->assertSame(self::$fileInfo[1]['fileName'], $file->getUTText()[0]['contentText'][0]['fileName']);
    }

    public function testSetThumbUrl()
    {
        $file = new File();

        $file->setFileUrl(self::$fileInfo[1]['url'])->setThumbUrl(self::$fileInfo[0]['url']);

        $this->assertSame(self::$fileInfo[1]['url'], $file->getUTText()[0]['contentText'][0]['url']);

        $this->assertSame(self::$fileInfo[0]['url'], $file->getUTText()[0]['contentText'][1]['url']);
    }

    public function testSetThumbSize()
    {
        $file = new File();

        $file->setThumbSize(self::$fileInfo[1]['fileSize']);

        $this->assertSame(self::$fileInfo[1]['fileSize'], $file->getUTText()[0]['contentText'][0]['fileSize']);
    }

    public function testSetThumbContentType()
    {
        $file = new File();

        $file->setThumbContentType(self::$fileInfo[1]['contentType']);

        $this->assertSame(self::$fileInfo[1]['contentType'], $file->getUTText()[0]['contentText'][0]['contentType']);
    }

    public function testSetThumbUntil()
    {
        $file = new File();

        $file->setThumbUntil(self::$fileInfo[1]['until']);

        $this->assertSame(self::$fileInfo[1]['until'], $file->getUTText()[0]['contentText'][0]['until']);
    }

    public function testSetThumbName()
    {
        $file = new File();

        $file->setThumbName(self::$fileInfo[1]['fileName']);

        $this->assertSame(self::$fileInfo[1]['fileName'], $file->getUTText()[0]['contentText'][0]['fileName']);
    }
}
