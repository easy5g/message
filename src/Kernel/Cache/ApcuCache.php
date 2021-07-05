<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 1:27 下午
 */

namespace Easy5G\Kernel\Cache;


use Symfony\Component\Cache\Adapter\ApcuAdapter;

class ApcuCache extends CacheAbstract
{
    public function __construct()
    {
        $this->cache = new ApcuAdapter(self::CACHE_NAMESPACE);
    }
}