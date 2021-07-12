<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Maap\Menu;


use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Maap\Application;
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
        $ISP = $ISP ?? $this->getDefaultISP();

        /** @var Application $app */
        $app = $this->app;

        if ($ISP !== Const5G::CM) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        } else {
            $menu = $app->chatbot->info($ISP, $url)->getMenu();
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
        $ISP = $ISP ?? $this->getDefaultISP();

        /** @var Application $app */
        $app = $this->app;

        if ($ISP !== Const5G::CM) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        } else {
            $menu = $app->chatbot->info($ISP, $url)->getMenu();
        }

        return $menu;
    }

    /**
     * create
     * @param $buttons
     * @param string|null $ISP
     * @param string|null $url
     * @return bool
     */
    public function create($buttons, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this method:' . explode('::', __METHOD__)[1]);
        }

        if ($url) {
            $client->setThirdUrl($url,'thirdCreateUrl');
        }

        /** @var Common $client */
        return $client->create($buttons);
    }
}
