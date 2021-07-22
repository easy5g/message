<?php
/**
 * User: zhouhua
 * Date: 2021/7/22
 * Time: 11:47 上午
 */

namespace Easy5G\Kernel\Contracts;


interface ChatbotMenuInterface
{
    public function parse(string $json): self;

    public function toArray(): array;

    public function toJson(): string;
}