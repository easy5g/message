<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Chatbot\Material;


use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\InvalidArgumentException;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\Collection;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Easy5G\Kernel\Support\Xml;
use Easy5G\Chatbot\Application;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface;

class ChinaMobile extends Client
{
    protected $uploadUrl = '%s/vg2/messaging/Content';
    protected $deleteUrl = '%s/vg2/messaging/Content';
    protected $downloadUrl = '%s/vg2/messaging/Content/downLoadRes';

    public $serviceProvider = Const5G::CM;

    /**
     * checkPath
     * @param string $path
     * @param string|null $thumbnailPath
     * @return array
     */
    protected function checkPath(string $path, ?string $thumbnailPath = null): array
    {
        foreach (func_get_args() as $checkPath) {
            if (!file_exists($checkPath) || !is_readable($checkPath)) {
                throw new InvalidArgumentException('File does not exist, or the file is unreadable:' . $checkPath);
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
            'User-Agent' => $app->config->get($this->serviceProvider . '.chatbotURI'),
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
            'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            'User-Agent' => $app->config->get($this->serviceProvider . '.chatbotURI'),
            'tid' => $media,
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
        if ($thumbnailPath) {
            $multipart[] = [
                'name' => 'Thumbnail',
                'content' => fopen($thumbnailPath, 'r'),
                'filename' => basename($thumbnailPath)
            ];
        }

        $multipart[] = [
            'name' => 'File',
            'content' => fopen($path, 'r'),
            'filename' => basename($path)
        ];

        return $multipart;
    }

    /**
     * getMaterial
     * @param string $resource
     * @return ResponseInterface
     * @throws BindingResolutionException|InvalidISPException|InvalidConfigException
     */
    protected function getMaterial(string $resource)
    {
        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->post($this->getCurrentUrl('download'), [
            'headers' => [
                'User-Agent' => $app->config->get($this->serviceProvider . '.chatbotURI'),
                'Authorization' => $app->access_token->getToken(),
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ],
        ], true);

        $httpStatusCode = $response->getStatusCode();

        if (!in_array($httpStatusCode, [200, 404, 410])) {
            throw new BadRequestException('Request to ' . $resource . ' return ' . $httpStatusCode . ' HTTP Status Code', $httpStatusCode);
        }

        return $response;
    }

    /**
     * downloadFail
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    protected function downloadFail(ResponseCollection $collect, ResponseInterface $response)
    {
        $collect->setStatusCode($response->getStatusCode())
            ->setResult(false)
            ->setMessage('Resource not found');
    }

    /**
     * notify
     * @param $callback
     * @return Response
     */
    public function notify($callback)
    {
        /** @var Application $app */
        $app = $this->app;

        $receiveData['chatbotURI'] = $app->request->query->get('chatbotURI');
        $receiveData['tid'] = $app->request->headers->get('tid');
        $receiveData['Authstatus'] = $app->request->headers->get('Authstatus');

        if ($receiveData['Authstatus'] === '0') {
            $fileInfo = Xml::parse($app->request->getContent());

            if ($fileInfo) {
                $receiveData['file-info'] = $fileInfo['file-info'];
            }
        }

        if (!is_null($callback)) {
            call_user_func($callback, new Collection($receiveData));
        }

        return new Response('', 200);
    }
}