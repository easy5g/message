<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Csp\Customer;


use Easy5G\Kernel\Support\Const5G;

class ChinaMobile extends Client
{
    protected $serviceProvider = Const5G::CM;

    protected function getUploadHeaders(int $uploadType): array
    {
        // TODO: Implement getUploadHeaders() method.
    }

    protected function getMaterial(string $resource)
    {
        // TODO: Implement getMaterial() method.
    }
}