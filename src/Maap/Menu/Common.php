<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Maap\Menu;


use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Maap\Application;
use Illuminate\Contracts\Container\BindingResolutionException;

trait Common
{
    /**
     * create
     * @param $buttons
     * @return string
     * @throws BindingResolutionException|InvalidISPException
     */
    public function create($buttons)
    {
        $buttonsArr = json_decode($buttons, true);

        if (empty($buttonsArr) && $buttonsArr !== []) {
            throw new BadRequestException('Incorrect data structure,buttons must be json');
        }

        /** @var Application $app */
        $app = $this->app;

        return $app->httpClient->post($this->getCurrentUrl('create'), [
            'json' => $buttons,
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ]);
    }
}