<?php
/**
 * User: zhouhua
 * Date: 2021/6/28
 * Time: 5:28 下午
 */

namespace Easy5G\Kernel;

use Easy5G\Kernel\Cache\CacheManager;
use Easy5G\Kernel\Config\Repository;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Factory\Chatbot\InfoFactory;
use Easy5G\Kernel\Factory\Chatbot\MenuFactory;
use Illuminate\Container\Container;
use Unit\Kernel\Log\LogManager;

/**
 * Class App
 * @package Easy5G\Kernel
 *
 * @property Repository $config
 * @property $this $app
 * @property CacheManager $cache
 * @property HttpClient $httpClient
 * @property LogManager $log
 * @property InfoFactory $chatbotInfoFactory
 * @property MenuFactory $chatbotMenuFactory
 */
abstract class App extends Container
{
    public $defaultISP;

    public function __construct()
    {
        $this->registerBaseBindings();
    }

    /**
     * hasInstance
     * @return bool
     */
    public static function hasInstance()
    {
        return isset(static::$instance);
    }

    /**
     * registerBaseBindings
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance(self::class, $this);

        $this->alias(self::class,'app');
    }

    /**
     * setDefaultISP 设置默认的ISP
     * @param $ISP
     * @throws InvalidISPException
     */
    public function setDefaultISP($ISP = null)
    {
        $ISPs = $this->config->getServiceProviders();

        if (empty($ISP)) {
            if (count($ISPs) !== 1) {
                throw new InvalidISPException('More than one ISP, please select manually');
            }

            $ISP = reset($ISPs);
        } else {
            if (!in_array($ISP, $ISPs)) {
                throw new InvalidISPException('Illegal ISP');
            }
        }

        $this->defaultISP = $ISP;
    }

    /**
     * getDefaultISP 获取默认的ISP
     * @param string|null $ISP
     * @return int
     * @throws InvalidISPException
     */
    public function getDefaultISP(?string $ISP = null)
    {
        if (!isset($this->defaultISP) || ($ISP && $this->defaultISP !== $ISP)) {
            $this->setDefaultISP($ISP);
        }

        return $this->defaultISP;
    }
}