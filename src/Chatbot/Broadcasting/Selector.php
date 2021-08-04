<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\Broadcasting;


use Easy5G\Kernel\Contracts\MessageInterface;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Illuminate\Contracts\Container\BindingResolutionException;

class Selector extends ISPSelector
{
    public $serviceMap = [
        Const5G::CM => ChinaMobile::class,
        Const5G::CU => ChinaUnicom::class,
        Const5G::CT => ChinaTelecom::class,
    ];

    /**
     * sendMessage
     * @param MessageInterface $message
     * @param array $sendInfo
     * @param string|null $ISP
     * @param string|null $url
     * @return ResponseCollection
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException
     */
    public function sendMessage(MessageInterface $message, array $sendInfo, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($url) {
            $client->setThirdUrl($url, 'send');
        }

        return $client->sendMessage($message, $sendInfo);
    }
}
