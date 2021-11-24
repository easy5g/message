<?php
/**
 * User: zhouhua
 * Date: 2021/8/9
 * Time: 4:34 下午
 */

namespace Unit\Chatbot\Structure\Message;

use Easy5G\Chatbot\Structure\Button;
use Easy5G\Chatbot\Structure\Menu;
use Easy5G\Chatbot\Structure\Message\Card;
use Easy5G\Chatbot\Structure\Message\CardContent;
use Easy5G\Kernel\Exceptions\CardException;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function testCardContentException()
    {
        $content = new CardContent();

        $cardData = [
            'media' => [
                'mediaUrl' => 'http://127.0.0.1/test',
                'mediaContentType' => 'mp4',
                'mediaFileSize' => 1024,
                'height' => 'SHORT_HEIGHT'
            ]
        ];

        try {
            $content->all();
        }catch (CardException $e) {
        }
        $this->assertSame('Media, title, description must have at least one item',$e->getMessage());

        try {
            $content->setMedia('test');
        }catch (CardException $e) {
        }
        $this->assertSame('Media must be an array',$e->getMessage());

        try {
            $content->setMediaUrl($cardData['media']['mediaUrl']);

            $content->all();
        }catch (CardException $e) {
        }
        $this->assertSame('Media content type must fill in',$e->getMessage());

        try {
            $content->setMediaContentType($cardData['media']['mediaContentType']);

            $content->all();
        }catch (CardException $e) {
        }
        $this->assertSame('Media file size must fill in',$e->getMessage());

        try {
            $content->setMediaFileSize($cardData['media']['mediaFileSize']);

            $content->all();
        }catch (CardException $e) {
        }
        $this->assertSame('Height must fill in',$e->getMessage());

        $content->setHeight($cardData['media']['height']);

        $this->assertSame($cardData,$content->all());

        $cardData['media']['thumbnailUrl'] = 'http://127.0.0.1/test1';
        $cardData['media']['thumbnailContentType'] = 'image';
        $cardData['media']['thumbnailFileSize'] = 1024;

        try {
            $content->setThumbnailUrl($cardData['media']['thumbnailUrl']);

            $content->all();
        }catch (CardException $e) {
        }
        $this->assertSame('When thumbnail url exists, thumbnail content type must be filled in',$e->getMessage());

        try {
            $content->setThumbnailContentType($cardData['media']['thumbnailContentType']);

            $content->all();
        }catch (CardException $e) {
        }
        $this->assertSame('When thumbnail url exists, thumbnail file size must be filled in',$e->getMessage());

        $content->setThumbnailFileSize($cardData['media']['thumbnailFileSize']);

        $this->assertSame($cardData,$content->all());

        $cardData['description'] = 'testDesc';
        $cardData['title'] ='testTitle';

        $content->setDescription($cardData['description']);
        $content->setTitle($cardData['title']);

        $this->assertSame($cardData,$content->all());

        $menu = new Menu(Menu::FIRST,'',true);
        $menu->addButton(Button::reply('test'));

        $cardData += $menu->toArray();

        $content->setSuggestions($menu);

        $this->assertSame($cardData,$content->all());
    }

    public function testCardContent()
    {
        $content = new CardContent();

        $media = [
            'mediaUrl' => 'http://127.0.0.1/test',
            'mediaContentType' => 'mp4',
            'mediaFileSize' => 1024,
            'height' => 'SHORT_HEIGHT'
        ];

        $content->setMediaUrl('http://127.0.0.1/test')
        ->setMediaContentType();
    }

    public function testAddContent()
    {
        $card= new Card();

        $content= new CardContent();

    }

    public function testParse()
    {

    }

    public function testAddLayout()
    {

    }

    public function testGetUTText()
    {

    }
}
