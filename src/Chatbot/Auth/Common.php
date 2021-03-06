<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Chatbot\Auth;


use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Exceptions\BadResponseException;
use Easy5G\Chatbot\Application;
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
                'Content-Type' => 'application/json',
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

        $verifyRes = self::verify($app);

        if ($verifyRes && !is_null($callback)) {
            $res = $verifyRes & call_user_func($callback);
        } else {
            $res = $verifyRes;
        }

        return new Response('', 200, [
            'echoStr' => $res ? $app->request->headers->get('echoStr') : '',
            'appId' => $res ? $config['appId'] : '',
        ]);
    }

    /**
     * verify 对服务器的请求进行验证
     * @param Application $app
     * @return bool
     * @throws BindingResolutionException
     * @throws InvalidISPException
     */
    public static function verify(Application $app)
    {
        $verifyData['nonce'] = $app->request->headers->get('nonce');
        $verifyData['timestamp'] = $app->request->headers->get('timestamp');
        $verifyData['token'] = $app->access_token->getToken();

        sort($verifyData, SORT_STRING);

        return hash('sha256', implode('', $verifyData)) === $app->request->headers->get('signature');
    }
}