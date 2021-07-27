<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Csp\Customer;


use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

class ChinaMobile extends Client
{
    protected $serviceProvider = Const5G::CM;

    protected function getUploadHeaders(int $uploadType): array
    {
        // TODO: Implement getUploadHeaders() method.
    }

    protected function getMaterial(string $resource): ResponseInterface
    {
        // TODO: Implement getMaterial() method.
    }

    protected function downloadFail(ResponseCollection $collect, ResponseInterface $response)
    {
        // TODO: Implement downloadFail() method.
    }
}