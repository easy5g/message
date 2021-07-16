<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\Auth;


use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Selector
 * @package Easy5G\Chatbot\Auth
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

    /**
     * notify
     * @param callable|null $callBack
     * @param string|null $ISP
     * @return Response
     * @throws BindingResolutionException|InvalidISPException
     */
    public function notify(?callable $callBack = null, ?string $ISP = null)
    {
        /** @var Common $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not need a access token callback');
        }

        return $client->notify($callBack);
    }
}