<?php
/**
 * User: zhouhua
 * Date: 2021/7/29
 * Time: 2:26 下午
 */

namespace Easy5G\Chatbot\Structure;


use Easy5G\Kernel\Contracts\MessageInterface;

class Text implements MessageInterface
{
    use MessageTrait;

    protected $contentType = 'text/plain';
    protected $contentEncoding;
    protected $contentText;

    /** @var Menu */
    protected $suggestions;

    public function __construct(string $content, string $encode = 'utf8')
    {
        $this->contentText = $content;

        $this->contentEncoding = $encode;
    }

    /**
     * getToHttpData
     * @return string
     */
    protected function getToHttpData()
    {
        return $this->contentText;
    }
}