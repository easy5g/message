<?php
/**
 * User: zhouhua
 * Date: 2021/8/3
 * Time: 2:53 下午
 */

namespace Easy5G\Chatbot\Structure;


use Easy5G\Kernel\Contracts\MessageInterface;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\Xml;

trait MessageTrait
{
    /**
     * getContentType
     * @param null $ISP
     * @return string
     * @throws InvalidISPException
     */
    public function getContentType($ISP = null): string
    {
        if (!isset($this->suggestions)) {
            return $this->contentType;
        }

        if (empty($ISP)) {
            throw new InvalidISPException('Illegal ISP');
        }

        if ($ISP === Const5G::CM) {
            return 'multipart/mixed;boundary="next"';
        } else {
            return $this->contentText;
        }
    }

    /**
     * getContentEncoding
     * @return string
     */
    public function getContentEncoding(): string
    {
        return $this->contentEncoding;
    }

    /**
     * addSuggestions
     * @param Menu $suggestions
     */
    public function addSuggestions(Menu $suggestions): void
    {
        $this->suggestions = $suggestions;
    }

    /**
     * addFallback
     * @param MessageInterface $fallback
     */
    public function addFallback(MessageInterface $fallback): void
    {
        $this->fallback = $fallback;
    }


    /**
     * getText
     * @param $ISP
     * @return array|string
     */
    public function getText($ISP)
    {
        if ($ISP === Const5G::CM) {
            return $this->getCMText();
        } else {
            return $this->getUTText();
        }
    }

    /**
     * getCMText
     * @return string
     */
    public function getCMText(): string
    {
        if (isset($this->suggestions)) {
            return Xml::cdata($this->combinedMData());
        }else{
            return Xml::cdata($this->getToHttpData());
        }
    }

    /**
     * getUTText
     * @return array
     */
    public function getUTText(): array
    {
        $text = [
            [
                'contentType' => $this->contentType,
                'contentEncoding' => $this->contentEncoding,
                'contentText' => $this->contentText
            ]
        ];

        if (isset($this->suggestions)) {
            $text[] = [
                'contentType' => 'application/vnd.gsma.botsuggestion.v1.0+json',
                'contentText' => $this->suggestions->toArray()
            ];
        }

        return $text;
    }

    /**
     * combinedMData
     * @return string
     */
    protected function combinedMData(): string
    {
        $cdata = "--next\r\n";

        $cdata .= $this->toHttp($this->getToHttpData());

        $cdata .= "\r\n--next\r\n";

        $cdata .= $this->suggestions->toHtml();

        $cdata .= "\r\n--next--";

        return $cdata;
    }

    /**
     * toHttp
     * @param $data
     * @return string
     */
    protected function toHttp($data)
    {
        $contentLen = strlen($data);

        $httpHeaders = "Content-Type:{$this->contentType}\r\n";

        $httpHeaders .= "Content-Disposition:inline;filename=\"Message\"\r\n";

        $httpHeaders .= "Content-Length:{$contentLen}\r\n";

        return $httpHeaders . "\r\n" . $data;
    }
}