<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 3:14 下午
 */

namespace Easy5G\Kernel\Cache;


use Easy5G\Kernel\Contracts\CacheInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\ItemInterface;

abstract class CacheAbstract implements CacheInterface
{
    const CACHE_NAMESPACE = 'easy5g';

    /** @var AbstractAdapter */
    public $cache;

    /**
     * set 设置缓存
     * @param string $key
     * @param $val
     * @param int $ttl
     * @return bool
     */
    public function set(string $key, $val, int $ttl = 0)
    {
        try {
            $firstSet = false;

            $this->cache->get($key, function (ItemInterface $item) use ($val, $ttl, &$firstSet) {
                if ($ttl > 0) {
                    $item->expiresAfter($ttl);
                }

                $firstSet = true;

                return $val;
            });

            if ($firstSet) {
                return true;
            }

            //如果存在旧缓存是不会覆盖的，需要先删除，在重新设置
            $this->del($key);

            return $this->set(...func_get_args());
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * get 获取缓存
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        try {
            return $this->cache->get($key, function () {
                return null;
            });
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * del 删除缓存
     * @param string $key
     * @return bool
     */
    public function del(string $key)
    {
        try {
            return $this->cache->delete($key);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}