<?php
/**
 * User: zhouhua
 * Date: 2021/7/22
 * Time: 11:23 上午
 */

namespace Easy5G\Kernel\Factory;


use Easy5G\Chatbot\Structure\Button;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\Const5G;

class ChatbotButtonFactory extends Factory
{
    public $serviceMap = [
        Const5G::CU => Button::class,
        Const5G::CT => Button::class,
    ];

    /**
     * create
     * @param array $data
     * @param string|null $ISP
     * @return Button
     * @throws InvalidISPException
     */
    public function create(array $data = [], ?string $ISP = null)
    {
        $serviceName = $this->getServiceName($ISP);

        return new $serviceName($data);
    }
}