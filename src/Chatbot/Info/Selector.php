<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\Info;


use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Chatbot\Structure\Info;
use Easy5G\Kernel\Support\ResponseCollection;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Selector
 * @package Easy5G\Info\Info
 */
class Selector extends ISPSelector
{
    public $serviceMap = [
        Const5G::CM => ChinaMobile::class,
        Const5G::CU => ChinaUnicom::class,
        Const5G::CT => ChinaTelecom::class,
    ];

    /**
     * all
     * @param string|null $ISP
     * @param string|null $url
     * @return Info
     * @throws BindingResolutionException|InvalidISPException
     */
    public function all(?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        }

        if ($url) {
            $client->setThirdUrl($url, 'query');
        }

        /** @var Common $client */
        return $client->all();
    }

    /**
     * update
     * @param $info
     * @param string|null $ISP
     * @param string|null $url
     * @return ResponseCollection
     * @throws BindingResolutionException|InvalidISPException|InvalidConfigException
     */
    public function update($info, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        }

        if ($url) {
            $client->setThirdUrl($url, 'update');
        }

        return $client->update($info);
    }

    /**
     * notify
     * @param callable|null $callback
     * @param string|null $ISP
     * @return Response
     * @throws BindingResolutionException|InvalidISPException
     */
    public function notify(?callable $callback = null, ?string $ISP = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        }

        /** @var Common $client */
        return $client->notify($callback);
    }
}
