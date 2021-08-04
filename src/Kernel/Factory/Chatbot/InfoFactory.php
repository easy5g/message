<?php

namespace Easy5G\Kernel\Factory\Chatbot;


use Easy5G\Chatbot\Structure\Info;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Factory\Factory;
use Easy5G\Kernel\Support\Const5G;

/**
 * User: zhouhua
 * Date: 2021/7/21
 * Time: 9:48 上午
 */
class InfoFactory extends Factory
{
    public static $serviceMap = [
        Const5G::CU => Info::class,
        Const5G::CT => Info::class,
    ];

    /**
     * create
     * @param array $data
     * @param string|null $ISP
     * @return Info
     * @throws InvalidISPException
     */
    public function create(array $data = [], ?string $ISP = null)
    {
        $serviceName = $this->getServiceName($ISP);

        return new $serviceName($data);
    }
}