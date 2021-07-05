<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 2:21 下午
 */

namespace Easy5G\Kernel\Contracts;


interface ConfigInterface
{
    /**
     * 确认配置是否存在
     * @param string $key
     * @return bool
     */
    public function has(string $key);

    /**
     * 获取配置
     * @param array|string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * 设置配置
     * @param array|string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value = null);

    /**
     * getServiceProviders 获取所有通讯服务商的配置
     * @return array
     */
    public function getServiceProviders(): array;

    /**
     * getServiceProvidersNum 获取通讯服务商的数量
     * @return int
     */
    public function getServiceProvidersNum(): int;
}