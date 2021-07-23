<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Csp\Customer;


use Easy5G\Csp\Application;
use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface;

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

    /**
     * getMaterial
     * @param string $resource
     * @return ResponseInterface|string
     * @throws BindingResolutionException|InvalidISPException
     */
    protected function getMaterial(string $resource)
    {
        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->post($this->getCurrentUrl('download'), [
            'headers' => [
                    'Content-Type' => 'application/json',
                    'authorization' => $app->access_token->getToken(),
                ] + $this->getCTCspVerifyHeader($app->access_token->getCredentials()['accessKey']),
            'json' => [
                'fileUrl' => $resource
            ]
        ], true);

        $httpStatusCode = $response->getStatusCode();

        if ($httpStatusCode !== 200) {
            throw new BadRequestException('Request to ' . $resource . ' return ' . $httpStatusCode . ' HTTP Status Code', $httpStatusCode);
        }

        if ($response->getBody()->read(1) === '{') {
            $response->getBody()->rewind();

            $content = $response->getBody()->getContents();

            if (json_decode($content,true)) {
                return $content;
            }
        }

        $response->getBody()->rewind();

        return $response;
    }
}