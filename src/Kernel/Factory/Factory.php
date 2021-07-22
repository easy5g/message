<?php
/**
 * User: zhouhua
 * Date: 2021/7/21
 * Time: 10:13 上午
 */

namespace Easy5G\Kernel\Factory;


use Easy5G\Kernel\App;
use Easy5G\Kernel\Exceptions\InvalidISPException;

abstract class Factory
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
}