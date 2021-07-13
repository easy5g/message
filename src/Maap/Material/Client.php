<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Maap\Material;


use Easy5G\Kernel\BaseClient;
use Easy5G\Kernel\Exceptions\InvalidArgumentException;

abstract class Client extends BaseClient
{
    public function upload($path)
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new InvalidArgumentException('File does not exist, or the file is unreadable:' . $path);
        }
    }
}