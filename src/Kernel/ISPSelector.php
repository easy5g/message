<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Kernel;


use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Maap\Auth\Client;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class ISPSelector
{
    public $app;

    protected $defaultISP;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * setDefaultISP 设置默认的ISP
     * @param $ISP
     * @throws InvalidISPException
     */
    public function setDefaultISP($ISP = null)
    {
        $ISPs = $this->app->config->getServiceProviders();

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
     * @return int
     * @throws InvalidISPException
     */
    public function getDefaultISP()
    {
        if (!isset($this->defaultISP)) {
            $this->setDefaultISP();
        }

        return $this->defaultISP;
    }

    /**
     * getServiceName 更具ISP获取相应的服务名
     * @param $ISP
     * @return mixed
     * @throws InvalidISPException
     */
    public function getServiceName($ISP = null)
    {
        if (
            (empty($ISP) && empty($ISP = $this->getDefaultISP()))
            || !isset($this->serviceMap[$ISP])
        ) {
            throw new InvalidISPException('Illegal ISP');
        }

        return $this->serviceMap[$ISP];
    }

    /**
     * register
     * @return $this
     */
    public function register()
    {
        foreach ($this->serviceMap as $serviceClass) {
            $this->app->singletonIf($serviceClass);
        }

        return $this;
    }

    /**
     * getClient
     * @param $ISP
     * @return mixed|object
     * @throws BindingResolutionException|InvalidISPException
     */
    protected function getClient($ISP)
    {
        $className = $this->getServiceName($ISP);

        /** @var Client $client */
        return $this->app->make($className);
    }
}