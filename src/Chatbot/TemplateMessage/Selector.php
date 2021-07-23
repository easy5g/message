<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\TemplateMessage;


use Easy5G\Kernel\Exceptions\InvalidConfigException;
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
     * batchSend
     * @param array $data
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|InvalidISPException
     */
    public function batchSend(array $data, ?string $ISP = null, ?string $url = null)
    {
        /** @var ChinaMobile $client */
        $client = $this->getClient($ISP);

        if (!$client instanceof ChinaMobile) {
            throw new InvalidISPException('Only China Mobile support this method:' . explode('::', __METHOD__)[1]);
        }

        if ($url) {
            $client->setThirdUrl($url, 'batchSend');
        }

        return $client->batchSend($data);
    }

    /**
     * batchReply
     * @param array $data
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException
     */
    public function batchReply(array $data, ?string $ISP = null, ?string $url = null)
    {
        /** @var ChinaMobile $client */
        $client = $this->getClient($ISP);

        if (!$client instanceof ChinaMobile) {
            throw new InvalidISPException('Only China Mobile support this method:' . explode('::', __METHOD__)[1]);
        }

        if ($url) {
            $client->setThirdUrl($url, 'batchReply');
        }

        return $client->batchReply($data);
    }
}
