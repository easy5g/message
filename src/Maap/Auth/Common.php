<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Maap\Auth;


use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Exceptions\BadResponseException;
use Easy5G\Maap\Application;
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
            'appId' => $config['appId'],
            'appKey' => $config['appKey'],
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

        $responseContent = $app->httpClient->post($this->getCurrentUrl('token'), [
            'json' => $this->getCredentials(),
            'headers' => [
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ]);

        $tokenData = json_decode($responseContent, true);

        if (empty($tokenData)) {
            throw new BadResponseException('Incorrect data structure');
        }

        if ($tokenData['errorCode'] !== 0) {
            throw new BadResponseException($tokenData['errorMessage'], $tokenData['errorCode']);
        }

        return [$tokenData['accessToken'], $tokenData['expires']];
    }

    /**
     * notify 进行access_token回调验证，在基础验证通过的情况下，可根据用户传入的回调函数来确认最终给服务器返回的是通过还是失败
     * @param callable $callback
     * @return Response
     * @throws BindingResolutionException|InvalidISPException
     */
    public function notify(?callable $callback = null)
    {
        /** @var Application $app */
        $app = $this->app;

        $config = $app->config->get($this->serviceProvider);

        $headers = [
            'signature' => $app->request->headers->get('signature'),
            'timestamp' => $app->request->headers->get('timestamp'),
            'nonce' => $app->request->headers->get('nonce'),
            'echoStr' => $app->request->headers->get('echoStr'),
        ];

        $verifyRes = self::verify($headers['signature'], $headers['timestamp'], $headers['nonce'], $app->access_token->getToken());

        if ($verifyRes && !is_null($callback)) {
            $res = $verifyRes & call_user_func($callback, $headers);
        } else {
            $res = $verifyRes;
        }

        return new Response('', 200, [
            'echoStr' => $res ? $headers['echoStr'] : '',
            'appId' => $res ? $config['appId'] : '',
        ]);
    }

    /**
     * verify 对服务器的请求进行验证
     * @param $signature
     * @param $timestamp
     * @param $nonce
     * @param $accessToken
     * @return bool
     */
    public static function verify($signature, $timestamp, $nonce, $accessToken)
    {
        $verifyData['nonce'] = $nonce;
        $verifyData['timestamp'] = $timestamp;
        $verifyData['token'] = $accessToken;

        sort($verifyData, SORT_STRING);

        return hash('sha256', implode('', $verifyData)) === $signature;
    }
}