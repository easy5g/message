<?php
/**
 * User: zhouhua
 * Date: 2021/7/22
 * Time: 11:47 上午
 */

namespace Easy5G\Kernel\Contracts;


use Easy5G\Kernel\Exceptions\MenuException;

interface ChatbotMenuInterface
{
    /**
     * parse
     * @param string $json
     * @return $this
     * @throws MenuException
     */
    public function parse(string $json): self;

    public function toArray(): array;

    public function toJson(): string;

    public function toHtml(): string;
}