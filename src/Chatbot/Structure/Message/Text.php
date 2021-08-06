<?php
/**
 * User: zhouhua
 * Date: 2021/7/29
 * Time: 2:26 下午
 */

namespace Easy5G\Chatbot\Structure\Message;


use Easy5G\Chatbot\Structure\Menu;
use Easy5G\Chatbot\Structure\MessageTrait;
use Easy5G\Kernel\Contracts\MessageInterface;

class Text implements MessageInterface
{
    use MessageTrait;

    protected $contentType = 'text/plain';
    protected $contentEncoding;
    protected $contentText;

    /** @var Menu */
    protected $suggestions;
    /** @var MessageInterface */
    public $fallback;

    public function __construct(string $content, string $encode = 'utf8')
    {
        $this->contentText = $content;

        $this->contentEncoding = $encode;
    }

    /**
     * getToHttpData
     * @return string
     */
    public function getToHttpData()
    {
        return $this->contentText;
    }
}