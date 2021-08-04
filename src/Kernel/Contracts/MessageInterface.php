<?php
/**
 * User: zhouhua
 * Date: 2021/7/8
 * Time: 4:46 下午
 */

namespace Easy5G\Kernel\Contracts;


use Easy5G\Chatbot\Structure\Menu;

interface MessageInterface
{
    /**
     * getContentType
     * @param null $ISP
     * @return string
     */
    public function getContentType($ISP = null): string;

    /**
     * getContentEncoding
     * @return string
     */
    public function getContentEncoding(): string;

    /**
     * addSuggestions
     * @param Menu $suggestions
     */
    public function addSuggestions(Menu $suggestions): void;

    /**
     * getText
     * @param $ISP
     * @return array|string
     */
    public function getText($ISP);

    /**
     * getCMText
     * @return string
     */
    public function getCMText(): string;

    /**
     * getUTText
     * @return array
     */
    public function getUTText(): array;
}