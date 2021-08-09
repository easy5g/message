<?php
/**
 * User: zhouhua
 * Date: 2021/8/6
 * Time: 4:12 下午
 */

namespace Unit\Chatbot\Structure\Message;


use Easy5G\Chatbot\Structure\Message\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function testText()
    {
        $text = new Text('test');

        $this->assertSame('test',$text->getToHttpData());
    }
}
