<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 1:26 下午
 */

namespace Easy5G\Kernel\Cache;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FileCache extends CacheAbstract
{
    public function __construct()
    {
        $this->cache = new FilesystemAdapter(self::CACHE_NAMESPACE);
    }
}