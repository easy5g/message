<?php
/**
 * User: zhouhua
 * Date: 2021/7/12
 * Time: 2:42 下午
 */

namespace Easy5G\Kernel;


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
    public function setThirdUrl($url,$name = 'thirdUrl')
    {
        $this->{$name} = $url;
    }
}