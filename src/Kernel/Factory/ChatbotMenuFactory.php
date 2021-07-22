<?php

namespace Easy5G\Kernel\Factory;


use Easy5G\Chatbot\Structure\Menu;
use Easy5G\Kernel\Contracts\ChatbotMenuInterface;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\Const5G;

/**
 * User: zhouhua
 * Date: 2021/7/21
 * Time: 9:48 上午
 */
class ChatbotMenuFactory extends Factory
{
    public $serviceMap = [
        Const5G::CU => Menu::class,
        Const5G::CT => Menu::class,
    ];

    /**
     * create
     * @param string $data
     * @param string|null $ISP
     * @return ChatbotMenuInterface
     * @throws InvalidISPException
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