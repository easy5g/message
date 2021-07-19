<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Chatbot\Material;


use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\InvalidArgumentException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\Collection;
use Easy5G\Chatbot\Application;
use Easy5G\Chatbot\Auth\Common as Auth;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

trait Common
{
    /**
     * checkPath
     * @param string $path
     * @param string|null $thumbnailPath
     * @return array
     */
    protected function checkPath(string $path, ?string $thumbnailPath = null): array
    {
        foreach (func_get_args() as $key => $checkPath) {
            if (!file_exists($checkPath) || !is_readable($checkPath)) {
                throw new InvalidArgumentException('File does not exist, or the file is unreadable:' . $checkPath);
            }
        }

        $type = explode('.', basename($path))[1] ?? null;

        if (!in_array($type, $this->allowTypes)) {
            throw new InvalidArgumentException('The file type "' . $type . '" does not allowed');
        }

        if (in_array($type, ['png', 'jpg', 'jpeg'])) {
            //如果上传的是图片，则不需要上传缩略图
            $thumbnailPath = null;
        } elseif (empty($thumbnailPath)) {
            //不是图片需要上传缩略图
            throw new InvalidArgumentException('The file needs to upload a thumbnail');
        } else {
            //有缩略图
            $thumbnailType = explode('.', basename($thumbnailPath))[1] ?? null;

            if (!in_array($thumbnailType, ['png', 'jpg', 'jpeg'])) {
                throw new InvalidArgumentException('The thumbnail type must be one of JPG, JPEG, PNG');
            }
        }

        return [$path, $thumbnailPath];
    }

    /**
     * getUploadHeaders
     * @return array
     * @throws BindingResolutionException|InvalidISPException
     */
    protected function getUploadHeaders(): array
    {
        /** @var Application $app */
        $app = $this->app;

        return [
            'Authorization' => $app->access_token->getToken(),
            'UploadMode' => $this->getPeriodOfValidity(),
            'Content-Type' => 'multipart/form-data',
            'Accept' => 'application/json',
            'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
        ];
    }

    /**
     * getDeleteHeaders
     * @param string $media
     * @return array
     * @throws BindingResolutionException|InvalidISPException
     */
    protected function getDeleteHeaders(string $media): array
    {
        /** @var Application $app */
        $app = $this->app;

        return [
            'Authorization' => $app->access_token->getToken(),
            'url' => $media,
            'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
        ];
    }

    /**
     * getMultipart
     * @param string $path
     * @param string|null $thumbnailPath
     * @return array
     */
    protected function getMultipart(string $path, ?string $thumbnailPath = null): array
    {
        $name = 1;

        if ($thumbnailPath) {
            $multipart[] = [
                'name' => (string)$name++,
                'content' => fopen($thumbnailPath, 'r'),
                'filename' => basename($thumbnailPath)
            ];
        }

        $multipart[] = [
            'name' => (string)$name,
            'content' => fopen($path, 'r'),
            'filename' => basename($path)
        ];

        return $multipart;
    }

    /**
     * set$periodOfValidity
     * @param $period
     */
    public function setPeriodOfValidity($period)
    {
        $this->periodOfValidity = $period;
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

        $response = $app->httpClient->get($this->getCurrentUrl('download'), [
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'url' => $resource,
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ],
        ], true);

        $httpStatusCode = $response->getStatusCode();

        if ($httpStatusCode !== 200) {
            throw new BadRequestException('Request to ' . $resource . ' return ' . $httpStatusCode . ' HTTP Status Code', $httpStatusCode);
        }

        //如果返回的是json则直接返回
        if (strpos($response->getHeaderLine('Content-Type'), 'application/json') !== false) {
            return $response->getBody()->getContents();
        }

        return $response;
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
}