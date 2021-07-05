<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 11:52 上午
 */

namespace Easy5G\Kernel\Contracts;


interface CacheInterface
{
    /**
     * get 获取缓存
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * set 设置缓存
     * @param string $key
     * @param $value
     * @param int $ttl
     * @return bool
     */
    public function set(string $key, $value, int $ttl = 0);

    /**
     * del 删除缓存
     * @param string $key
     * @return bool
     */
    public function del(string $key);
}