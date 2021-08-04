<?php

namespace Easy5G\Kernel\Factory\Chatbot;


use Easy5G\Chatbot\Structure\Menu;
use Easy5G\Kernel\Contracts\ChatbotMenuInterface;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Exceptions\MenuException;
use Easy5G\Kernel\Factory\Factory;
use Easy5G\Kernel\Support\Const5G;

/**
 * User: zhouhua
 * Date: 2021/7/21
 * Time: 9:48 上午
 */
class MenuFactory extends Factory
{
    public static $serviceMap = [
        Const5G::CU => Menu::class,
        Const5G::CT => Menu::class,
    ];

    /**
     * create
     * @param string $data
     * @param string|null $ISP
     * @return ChatbotMenuInterface
     * @throws InvalidISPException|MenuException
     */
    public function create(string $data = '', ?string $ISP = null)
    {
        $serviceName = $this->getServiceName($ISP);

        /** @var ChatbotMenuInterface $instance */
        $instance = new $serviceName();

        if (empty($data)) {
            return $instance;
        }else{
            return $instance->parse($data);
        }
    }
}