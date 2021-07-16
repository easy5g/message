<?php
/**
 * User: zhouhua
 * Date: 2021/6/28
 * Time: 5:28 下午
 */

namespace Easy5G\Chatbot;

use Easy5G\Kernel\App;
use Easy5G\Kernel\Contracts\ConfigInterface;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Application
 * @package Easy5G\Chatbot
 *
 * @property Base\Selector base
 * @property Auth\Selector access_token
 * @property Server\Selector server
 * @property Chatbot\Selector chatbot
 * @property Menu\Selector menu
 * @property Request request
 */
class Application extends App
{
    public function __construct()
    {
        parent::__construct();

        $this->registerServiceProviders();
    }

    /**
     * registerServiceProviders 注册服务
     */
    protected function registerServiceProviders()
    {
        (new ChatbotProvider($this))->register();
    }

    /**
     * registerConfigRepository 注册配置仓库
     * @param $config
     * @throws InvalidConfigException
     */
    public function registerConfigRepository($config)
    {
        $configInstance = new Config($config);

        $this->instance('config', $configInstance);

        $this->instance(ConfigInterface::class, $configInstance);
    }
}