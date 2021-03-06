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
    public function getCredentials(): array
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

        $credentials = $this->getCredentials();

        $responseContent = $app->httpClient->post($this->getCurrentUrl('token'), [
            'json' => $credentials,
            'headers' => ['Content-Type' => 'application/json'] + $this->getCTCspVerifyHeader($credentials['accessKey'])
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