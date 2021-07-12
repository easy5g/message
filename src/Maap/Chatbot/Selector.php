<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Maap\Chatbot;


use Easy5G\Kernel\Exceptions\InvalidInfoException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Exceptions\BadResponseException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Maap\Structure\Info;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class Selector
 * @package Easy5G\Maap\Chatbot
 */
class Selector extends ISPSelector
{
    public $serviceMap = [
        Const5G::CM => ChinaMobile::class,
        Const5G::CU => ChinaUnicom::class,
        Const5G::CT => ChinaTelecom::class,
    ];

    /**
     * info
     * @param string|null $ISP
     * @param string|null $url
     * @return Info
     * @throws BindingResolutionException|InvalidISPException
     */
    public function info(?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        }

        if ($url) {
            $client->setThirdUrl($url,'thirdQueryUrl');
        }

        /** @var Common $client */
        return $client->info();
    }

    /**
     * updateInfo
     * @param array|Info $info
     * @param string|null $ISP
     * @param string|null $url
     * @return bool
     * @throws BindingResolutionException|InvalidISPException|InvalidInfoException|BadResponseException
     */
    public function updateInfo($info, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        }

        if ($url) {
            $client->setThirdUrl($url,'thirdUpdateUrl');
        }

        /** @var Common $client */
        return $client->updateInfo($info);
    }
}
