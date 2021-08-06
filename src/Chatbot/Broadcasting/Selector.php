<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\Broadcasting;


use Easy5G\Chatbot\Structure\Menu;
use Easy5G\Chatbot\Structure\Message\Card;
use Easy5G\Chatbot\Structure\Message\File;
use Easy5G\Chatbot\Structure\Message\Text;
use Easy5G\Kernel\Contracts\MessageInterface;
use Easy5G\Kernel\Exceptions\CardException;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Exceptions\MenuException;
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

    /**
     * sendText
     * @param $text
     * @param array $sendInfo
     * @param string|Menu|null $suggestions
     * @param string|null $ISP
     * @param string|null $url
     * @return ResponseCollection
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException|MenuException
     */
    public function sendText($text, array $sendInfo, $suggestions = null, ?string $ISP = null, ?string $url = null)
    {
        $textMsg = new Text($text);

        if (!empty($suggestions)) {
            $menu = $suggestions instanceof Menu ? $suggestions : (new Menu())->parse($suggestions);

            $textMsg->addSuggestions($menu);
        }

        return $this->sendMessage($textMsg, $sendInfo, $ISP, $url);
    }

    /**
     * sendFile
     * @param array $file
     * @param array $sendInfo
     * @param null $suggestions
     * @param string|null $ISP
     * @param string|null $url
     * @return ResponseCollection
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException|MenuException
     */
    public function sendFile(array $file, array $sendInfo, $suggestions = null, ?string $ISP = null, ?string $url = null)
    {
        $fileMsg = new File($file);

        if (!empty($suggestions)) {
            $menu = $suggestions instanceof Menu ? $suggestions : (new Menu())->parse($suggestions);

            $fileMsg->addSuggestions($menu);
        }

        return $this->sendMessage($fileMsg, $sendInfo, $ISP, $url);
    }

    /**
     * sendCard
     * @param array|string $card
     * @param array $sendInfo
     * @param null $suggestions
     * @param string|null $ISP
     * @param string|null $url
     * @return ResponseCollection
     * @throws BindingResolutionException|CardException|InvalidConfigException|InvalidISPException|MenuException
     */
    public function sendCard($card, array $sendInfo, $suggestions = null, ?string $ISP = null, ?string $url = null)
    {
        $cardMsg = new Card($card);

        if (!empty($suggestions)) {
            $menu = $suggestions instanceof Menu ? $suggestions : (new Menu())->parse($suggestions);

            $cardMsg->addSuggestions($menu);
        }

        return $this->sendMessage($cardMsg, $sendInfo, $ISP, $url);
    }
}
