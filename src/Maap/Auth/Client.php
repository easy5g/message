<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Maap\Auth;


use Easy5G\Maap\Application;
use Illuminate\Container\Container;

abstract class Client
{
    const TOKEN_PREFIX = 'easy5g.access_token.';

    /** @var Application */
    public $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * getCredentials 获取认证字段
     * @return array
     */
    abstract protected function getCredentials(): array;

    /**
     * requestToken 向上游请求token
     * @return array
     */
    abstract protected function requestToken(): array;

    /**
     * setThirdUrl 设置非官方的url
     * @param $url
     */
    public function setThirdUrl($url)
    {
        $this->thirdUrl = $url;
    }

    /**
     * getToken
     * @param $refresh
     * @return string
     */
    public function getToken($refresh)
    {
        $key = self::TOKEN_PREFIX . md5(json_encode($this->getCredentials()));

        if ($refresh || ($token = $this->app->cache->get($key)) === null) {
            [$token, $ttl] = $this->requestToken();

            $this->app->cache->set($key, $token, $ttl);
        }

        return $token;
    }
}