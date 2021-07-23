<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Csp\Auth;


use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Exceptions\BadResponseException;
use Easy5G\Csp\Application;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Response;

trait Common
{
    /**
     * getCredentials
     * @return array
     */
    protected function getCredentials(): array
    {
        $config = $this->app->config->get($this->serviceProvider);

        return [
            'cspId' => $config['cspId'],
            'accessKey' => $config['accessKey'],
        ];
    }

    /**
     * requestToken
     * @return array
     * @throws BadResponseException|BadRequestException
     */
    protected function requestToken(): array
    {
        /** @var Application $app */
        $app = $this->app;

        $date = date('YmdHis');

        $nonce = $date . str_pad(mt_rand(0, 99999999), 8, '0');

        $credentials = $this->getCredentials();

        $responseContent = $app->httpClient->post($this->getCurrentUrl('token'), [
            'json' => $credentials,
            'headers' => [
                'Content-Type' => 'application/json',
                'timestamp' => $date,
                'nonce' => $nonce,
                'signature' => md5($credentials['accessKey'] . $nonce . $date)
            ]
        ]);

        $tokenData = json_decode($responseContent, true);

        if (empty($tokenData)) {
            throw new BadResponseException('Incorrect data structure');
        }

        if ($tokenData['code'] !== 0) {
            throw new BadResponseException($tokenData['message'], $tokenData['code']);
        }

        return [$tokenData['data']['accessToken'], 7200];
    }
}