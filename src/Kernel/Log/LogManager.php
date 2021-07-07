<?php
/**
 * User: zhouhua
 * Date: 2021/7/7
 * Time: 10:57 上午
 */

namespace Unit\Kernel\Log;


use Easy5G\Kernel\App;

class LogManager
{
    /** @var App */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function confirmDrive()
    {

    }

    public function debug($message, array $context = [])
    {
        return true;
    }
}