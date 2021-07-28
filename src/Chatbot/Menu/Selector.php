<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\Menu;


use Easy5G\Kernel\Contracts\ChatbotMenuInterface;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Chatbot\Application;
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
     * list
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|InvalidISPException
     */
    public function list(?string $ISP = null, ?string $url = null)
    {
        /** @var Application $app */
        $app = $this->app;

        $ISP = $app->getDefaultISP($ISP);

        if ($ISP === Const5G::CM) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        } else {
            $menu = $app->info->all($ISP, $url)->getMenu();
        }

        return $menu;
    }

    /**
     * current
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|InvalidISPException
     */
    public function current(?string $ISP = null, ?string $url = null)
    {
        /** @var Application $app */
        $app = $this->app;

        $ISP = $app->getDefaultISP($ISP);

        if ($ISP === Const5G::CM) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        } else {
            $menu = $app->info->all($ISP, $url)->getMenu();
        }

        return $menu;
    }

    /**
     * create
     * @param $menu
     * @param string|null $ISP
     * @param string|null $url
     * @return ResponseCollection
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException
     */
    public function create($menu, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        }

        if ($url) {
            $client->setThirdUrl($url, 'create');
        }

        if (is_string($menu)) {
            $menu = $this->app->chatbotMenuFactory->create($menu);
        }

        return $client->create($menu);
    }
}
