<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Chatbot\Info;


use Easy5G\Kernel\Contracts\InfoInterface;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Exceptions\BadResponseException;
use Easy5G\Chatbot\Application;
use Easy5G\Chatbot\Auth\Common as Auth;
use Easy5G\Chatbot\Structure\Info;
use Easy5G\Kernel\Support\Collection;
use Easy5G\Kernel\Support\ResponseCollection;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

trait Common
{
    /**
     * all
     * @return Info
     * @throws BindingResolutionException|InvalidISPException
     */
    public function all()
    {
        /** @var Application $app */
        $app = $this->app;

        $responseContent = $app->httpClient->get($this->getCurrentUrl('query'), [
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ]);

        $infoArr = json_decode($responseContent, true);

        if (empty($infoArr)) {
            throw new BadResponseException('Incorrect data structure');
        }

        return $app->chatbotInfoFactory->create()->assignment($infoArr);
    }

    /**
     * getUpdateRequestData
     * @param InfoInterface $info
     * @return array
     */
    protected function getUpdateRequestData(InfoInterface $info): array
    {
        return [
            'json' => $info->toJson(),
            'headers' => [
                'Authorization' => $this->app->access_token->getToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ];
    }

    /**
     * notify
     * @param $callback
     * @return Response
     * @throws BindingResolutionException|InvalidISPException
     */
    public function notify($callback)
    {
        /** @var Application $app */
        $app = $this->app;

        if (Auth::verify($app) && !is_null($callback)) {
            call_user_func($callback, new Collection(json_decode($app->request->getContent(), true)));
        }

        return new Response('', 200);
    }

    /**
     * updateResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    protected function updateResponse(ResponseCollection $collect, ResponseInterface $response)
    {
        $this->ctBaseResponse(...func_get_args());
    }
}