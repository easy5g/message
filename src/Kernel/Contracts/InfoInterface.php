<?php
/**
 * User: zhouhua
 * Date: 2021/7/8
 * Time: 4:46 下午
 */

namespace Easy5G\Kernel\Contracts;


interface InfoInterface
{
    /**
     * toJson
     * @param int $option
     * @return false|string
     */
    public function toJson(int $option = JSON_UNESCAPED_UNICODE);
}