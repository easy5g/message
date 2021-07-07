<?php
/**
 * User: zhouhua
 * Date: 2021/7/7
 * Time: 11:28 上午
 */

namespace Easy5G\Kernel\Cache;


use Easy5G\Kernel\App;
use Easy5G\Kernel\Contracts\CacheInterface;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Arr;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\ProxyAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Exception\CacheException;
use Symfony\Contracts\Cache\ItemInterface;

class CacheManager implements CacheInterface
{
    const CACHE_NAMESPACE = 'easy5g';

    protected $app;
    /** @var AbstractAdapter */
    protected $cacheDriver;

    /**
     * CacheManager constructor.
     * @param App $app
     * @throws InvalidConfigException
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        $this->confirmDrive();
    }

    /**
     * confirmDrive
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function confirmDrive()
    {
        $config = $this->app->config;

        $defaultEnv = $config->get('cache.default', 'dev');

        $cacheConfig = $config->get('cache.' . $defaultEnv, []);

        $adapterName = ucfirst(strtolower(Arr::get($cacheConfig, 'driver', 'filesystem')));

        $driverMethod = 'create' . $adapterName . 'Driver';

        if (method_exists($this, $driverMethod)) {
            try {
                return $this->{$driverMethod}($cacheConfig);
            } catch (Exception $e) {
                throw new InvalidConfigException($e->getMessage());
            }
        }

        throw new InvalidConfigException('Cache Driver [' . $adapterName . '] is not supported');
    }

    /**
     * createArrayDriver
     */
    protected function createArrayDriver()
    {
        $this->cacheDriver = new ArrayAdapter();
    }

    /**
     * createFilesystemDriver
     * @param array $config
     */
    protected function createFilesystemDriver(array $config)
    {
        $this->cacheDriver = new FilesystemAdapter(
            Arr::get($config, 'name', self::CACHE_NAMESPACE),
            0,
            Arr::get($config, 'path')
        );
    }

    /**
     * createApcuDriver
     * @param array $config
     * @throws CacheException
     */
    protected function createApcuDriver(array $config)
    {
        $this->cacheDriver = new ApcuAdapter(
            Arr::get($config, 'name', self::CACHE_NAMESPACE)
        );
    }

    /**
     * createRedisDriver
     * @param array $config
     */
    protected function createRedisDriver(array $config)
    {
        $this->cacheDriver = new RedisAdapter(
            Arr::get($config, 'connect'),
            Arr::get($config, 'name', self::CACHE_NAMESPACE)
        );
    }

    /**
     * createChainDriver
     * @param array $config
     */
    protected function createChainDriver(array $config)
    {
        $adapters = [];

        foreach ($config as $adapterConfig) {
            if (empty($adapterConfig['driver'])) {
                continue;
            }

            $adapterName = ucfirst(strtolower($adapterConfig['driver']));

            $driverMethod = 'create' . $adapterName . 'Driver';

            if (method_exists($this, $driverMethod)) {
                $this->{$driverMethod}($adapterConfig);

                $adapters[] = $this->cacheDriver;
            }
        }

        $this->cacheDriver = new ChainAdapter($adapters);
    }

    /**
     * createProxyDriver
     * @param array $config
     */
    protected function createProxyDriver(array $config)
    {
        $this->cacheDriver = new ProxyAdapter(
            $config['cache'],
            Arr::get($config, 'name', self::CACHE_NAMESPACE)
        );
    }

    /**
     * createDoctrineDriver
     * @param array $config
     */
    protected function createDoctrineDriver(array $config)
    {
        $this->cacheDriver = new DoctrineAdapter(
            $config['cache'],
            Arr::get($config, 'name', self::CACHE_NAMESPACE)
        );
    }

    /**
     * driver
     * @return AbstractAdapter
     */
    public function driver()
    {
        return $this->cacheDriver;
    }

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

            $this->driver()->get($key, function (ItemInterface $item) use ($val, $ttl, &$firstSet) {
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
            return $this->driver()->get($key, function () {
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
            return $this->driver()->delete($key);
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}