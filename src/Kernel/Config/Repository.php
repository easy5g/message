<?php
/**
 * User: zhouhua
 * Date: 2021/6/28
 * Time: 5:28 下午
 */

namespace Easy5G\Kernel\Config;


use ArrayAccess;
use Easy5G\Kernel\Contracts\ConfigInterface;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Arr;
use Easy5G\Kernel\Support\Const5G;

class Repository implements ConfigInterface, ArrayAccess
{
    protected $config = [];
    protected $serviceProviders = [];
    protected $serviceProvidersNum = 0;
    protected $legalServiceProviders = [
        Const5G::CM => Const5G::CM,
        Const5G::CU => Const5G::CU,
        Const5G::CT => Const5G::CT,
    ];

    /**
     * ISPSelector constructor.
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;

        $this->statisticsServiceProviders();

        $this->checkConfig();
    }

    protected function statisticsServiceProviders()
    {
        $this->serviceProviders = array_intersect_key($this->legalServiceProviders, $this->config);

        $this->serviceProvidersNum = count($this->serviceProviders);
    }

    /**
     * checkConfig 校验传入的配置
     * @throws InvalidConfigException
     */
    public function checkConfig()
    {
        if ($this->serviceProvidersNum === 0) {
            throw new InvalidConfigException('At least one service provider is required');
        }
    }

    /**
     * has 配置中是否存在key
     * @param string $key
     * @return bool
     */
    public function has(string $key)
    {
        return Arr::has($this->config, $key);
    }

    /**
     * get 获取配置
     * @param string $key
     * @param null $default
     * @return array|ArrayAccess|mixed|null
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * set 设置配置
     * @param string $key
     * @param null $value
     */
    public function set(string $key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set($this->config, $key, $value);
        }

        foreach ($this->legalServiceProviders as $sp) {
            if (strpos($key,$sp) === 0) {
                $this->statisticsServiceProviders();

                $this->checkConfig();
            }
        }
    }

    public function offsetExists($key)
    {
        return $this->has($key);
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->set($key, null);
    }

    /**
     * getServiceProviders 获取存在配置的服务商
     * @return array
     */
    public function getServiceProviders(): array
    {
        return $this->serviceProviders;
    }

    /**
     * getServiceProvidersNum 获取存在配置的服务商个数
     * @return int
     */
    public function getServiceProvidersNum(): int
    {
        return $this->serviceProvidersNum;
    }
}