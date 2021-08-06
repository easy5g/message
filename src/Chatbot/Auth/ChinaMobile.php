<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Chatbot\Auth;


use Easy5G\Kernel\Support\Const5G;

class ChinaMobile extends Client
{
    const TOKEN_EXPIRE_TIME = 7200;

    protected $serviceProvider = Const5G::CM;

    /**
     * getCredentials
     * @return array
     */
    protected function getCredentials(): array
    {
        $config = $this->app->config->get($this->serviceProvider);

        return [
            'appId' => $config['appId'],
            'password' => $config['password'],
        ];
    }

    /**
     * requestToken
     * @return array
     */
    public function requestToken(): array
    {
        return [$this->sign(), self::TOKEN_EXPIRE_TIME];
    }

    /**
     * sign
     * @return string
     */
    protected function sign()
    {
        $credentials = $this->getCredentials();

        $token = hash('sha256',$credentials['password']);

        $date = gmdate('D, d M Y H:i:s', time()) . ' GMT';

        $sha256 = hash('sha256', $token . $date);

        $base64 = base64_encode($credentials['appId'] . ':' . $sha256);

        return 'Basic ' . $base64;
    }
}