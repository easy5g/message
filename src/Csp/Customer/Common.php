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
use Easy5G\Kernel\Support\ResponseCollection;
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
     * @return ResponseInterface
     * @throws BindingResolutionException|InvalidISPException
     */
    protected function getMaterial(string $resource):ResponseInterface
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

        //如果是以{打头则认为是json
        if ($response->getBody()->read(1) === '{') {
            $response->getBody()->rewind();

            $response = $response->withHeader('Content-Type','application/json')->withStatus(404);
        }else{
            $response->withoutHeader('Content-Type');
        }

        $response->getBody()->rewind();

        return $response;
    }

    /**
     * downloadFail
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    protected function downloadFail(ResponseCollection $collect, ResponseInterface $response)
    {
        $raw = $response->getBody()->getContents();

        $data = json_decode($raw, true);

        $collect->setStatusCode(200)
            ->setRaw($raw)
            ->setResult(false)
            ->setCode($data['code'])
            ->setMessage($data['message']);
    }
}