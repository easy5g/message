<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Csp\Customer;


use Easy5G\Csp\Application;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Illuminate\Contracts\Container\BindingResolutionException;

trait Common
{
    /**
     * getUploadHeaders
     * @param int $uploadType
     * @return array
     * @throws BindingResolutionException|InvalidISPException
     */
    public function getUploadHeaders(int $uploadType): array
    {
        /** @var Application $app */
        $app = $this->app;

        return [
                'Content-Type' => 'multipart/form-data',
                'authorization' => $app->access_token->getToken(),
                'uploadType' => $uploadType,
            ] + $this->getCTCspVerifyHeader($app->access_token->getCredentials()['accessKey']);
    }
}