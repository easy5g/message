<?php
/**
 * User: zhouhua
 * Date: 2021/7/8
 * Time: 4:46 下午
 */

namespace Easy5G\Kernel\Contracts;


interface HttpClientInterface
{
    public function get(string $url, array $options = []);

    public function post(string $url, array $options = []);
}