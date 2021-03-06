<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Csp\Auth;


use Easy5G\Kernel\BaseClient;

abstract class Client extends BaseClient
{
    const TOKEN_PREFIX = 'easy5g.cps.access_token.';

    /**
     * getCredentials 获取认证字段
     * @return array
     */
    abstract public function getCredentials(): array;

    /**
     * requestToken 向上游请求token
     * @return array
     */
    abstract protected function requestToken(): array;

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

            $ttl > 100 && $ttl -= 100;

            $this->app->cache->set($key, $token, $ttl);
        }

        return $token;
    }
}