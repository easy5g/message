<?php
/**
 * User: zhouhua
 * Date: 2021/6/28
 * Time: 5:28 下午
 */

namespace Easy5G\Maap;

use Easy5G\Kernel\App;
use Easy5G\Kernel\Contracts\ConfigInterface;
use Easy5G\Kernel\Exceptions\InvalidConfigException;

/**
 * Class Application
 * @package Easy5G\Maap
 *
 * @property Base\Selector base
 * @property Auth\Selector access_token
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
        (new MaapProvider($this))->register();
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