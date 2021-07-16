<?php
/**
 * User: zhouhua
 * Date: 2021/7/12
 * Time: 2:42 下午
 */

namespace Easy5G\Kernel;


use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Maap\Application;

abstract class BaseClient
{
    public $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * setThirdUrl
     * @param $url
     * @param string $name
     */
    public function setThirdUrl($url, $name)
    {
        $this->{$this->getThirdUrlName($name)} = $url;
    }

    /**
     * getThirdUrl
     * @param $name
     * @return mixed
     */
    public function getThirdUrl($name)
    {
        return $this->{$this->getThirdUrlName($name)} ?? null;
    }

    /**
     * getUrl
     * @param $name
     * @return mixed
     */
    public function getUrl($name)
    {
        return $this->{$name . 'Url'};
    }

    /**
     * getThirdUrlName
     * @param string $name
     * @return string
     */
    protected function getThirdUrlName(string $name)
    {
        return 'third' . ucfirst($name) . 'Url';
    }

    /**
     * getCurrentUrl
     * @param $name
     * @return string
     * @throws InvalidConfigException
     */
    public function getCurrentUrl($name)
    {
        if ($thirdUrl = $this->getThirdUrl($name)) {
            return $thirdUrl;
        }

        /** @var Application $app */
        $app = $this->app;

        $config = $app->config->get($this->serviceProvider);

        $url = $this->getUrl($name);

        if (empty($url)) {
            throw new InvalidConfigException('The correct URL is not configured here, name:' . $name);
        }

        if ($this->serviceProvider === Const5G::CM) {
            return sprintf($this->getUrl($name), $config['serverRoot']);
        } else {
            return sprintf($this->getUrl($name), $config['serverRoot'], $config['apiVersion'], $config['chatbotId']);
        }
    }
}