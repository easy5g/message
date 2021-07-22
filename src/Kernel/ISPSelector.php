<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Kernel;


use Easy5G\Kernel\Exceptions\InvalidISPException;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class ISPSelector
{
    public $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * getServiceName 根据ISP获取相应的服务名
     * @param $ISP
     * @return mixed
     * @throws InvalidISPException
     */
    public function getServiceName($ISP = null)
    {
        if (
            empty($ISP = $this->app->getDefaultISP($ISP))
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

        return $this->app->make($className);
    }
}