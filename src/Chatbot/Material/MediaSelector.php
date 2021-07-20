<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\Material;


use Easy5G\Kernel\Support\Const5G;

class MediaSelector extends Selector
{
    public function register()
    {
        foreach ($this->serviceMap as $key => $serviceClass) {
            if ($key === Const5G::CM) {
                $this->app->singletonIf($serviceClass);
            } else {
                $this->app->singletonIf($serviceClass, function () use ($serviceClass) {
                    $instance = new $serviceClass($this->app);

                    $instance->setPeriodOfValidity('temp');

                    return $instance;
                });
            }
        }

        return $this;
    }
}
