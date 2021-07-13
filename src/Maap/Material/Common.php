<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Maap\Material;


use Easy5G\Maap\Application;

trait Common
{
    /**
     * getUploadUrl 获取请求地址
     * @return string
     */
    protected function getUploadUrl()
    {
        if (isset($this->thirdUploadUrl)) {
            return $this->thirdUploadUrl;
        }

        /** @var Application $app */
        $app = $this->app;

        $config = $app->config->get($this->serviceProvider);

        return sprintf(static::UPLOAD_URL, $config['url'], $config['apiVersion'], $config['chatbotId']);
    }
}