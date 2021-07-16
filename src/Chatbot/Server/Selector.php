<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\Server;


use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;

class Selector extends ISPSelector
{
    public $serviceMap = [
        Const5G::CM => ChinaMobile::class,
        Const5G::CU => ChinaUnicom::class,
        Const5G::CT => ChinaTelecom::class,
    ];

    public function serve()
    {
        $this->app->log->debug('Request received:', [
            'method' => $this->app->request->getMethod(),
            'uri' => $this->app->request->getUri(),
            'content-type' => $this->app->request->getContentType(),
            'content' => $this->app->request->getContent(),
        ]);
    }

    public function push()
    {

    }
}
