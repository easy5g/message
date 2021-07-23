<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Csp\Auth;


use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class Selector
 * @package Easy5G\Info\Auth
 */
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
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|InvalidISPException
     */
    public function getToken(?bool $refresh = false, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($url) {
            $client->setThirdUrl($url,'token');
        }

        return $client->getToken($refresh);
    }
}