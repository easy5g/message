<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Maap\Auth;


use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Illuminate\Contracts\Container\BindingResolutionException;

class Selector extends ISPSelector
{
    public $serviceMap = [
        Const5G::CM => ChinaMobile::class,
        Const5G::CU => ChinaUnicom::class,
        Const5G::CT => ChinaTelecom::class,
    ];

    /**
     * getToken
     * @param bool $refresh
     * @param null $ISP
     * @param null $url
     * @return string
     * @throws BindingResolutionException
     * @throws InvalidISPException
     */
    public function getToken($refresh = false, $ISP = null, $url = null)
    {
        $className = $this->getServiceName($ISP);

        /** @var Client $client */
        $client = $this->app->make($className);

        if ($url) {
            $client->setThirdUrl($url);
        }

        return $client->getToken($refresh);
    }
}