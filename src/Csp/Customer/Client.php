<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Csp\Customer;


use Easy5G\Csp\Application;
use Easy5G\Kernel\BaseClient;
use Easy5G\Kernel\Exceptions\CommonException;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\File;
use Easy5G\Kernel\Support\ResponseCollection;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface;

abstract class Client extends BaseClient
{
    /**
     * getUploadHeaders
     * @param int $uploadType
     * @return array
     */
    abstract protected function getUploadHeaders(int $uploadType): array;

    /**
     * getMaterial
     * @param string $resource
     * @return ResponseInterface
     */
    abstract protected function getMaterial(string $resource): ResponseInterface;

    /**
     * downloadFail
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    abstract protected function downloadFail(ResponseCollection $collect, ResponseInterface $response);

    /**
     * upload
     * @param string $path
     * @param int $uploadType
     * @return ResponseCollection
     * @throws CommonException|InvalidConfigException
     */
    public function upload(string $path, int $uploadType)
    {
        if (!File::readable($path)) {
            throw new CommonException('Unable to get file:' . $path);
        }

        if (!empty($thumbnailPath) && !File::readable($thumbnailPath)) {
            throw new CommonException('Unable to get file:' . $thumbnailPath);
        }

        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->post($this->getCurrentUrl('upload'), [
            'body' => file_get_contents($path),
            'headers' => $this->getUploadHeaders($uploadType)
        ]);

        return $this->returnCollect($response, function (ResponseCollection $collect, ResponseInterface $response) {
            $collect->setRaw($response->getBody()->getContents())
                ->setStatusCode(200);

            $data = json_decode($collect->getRaw(), true);

            $collect->setResult($data['code'] === 0)
                ->setMessage($data['message'] ?? '');

            if ($collect->getResult()) {
                $collect->set('url', $data['data']['url']);
            }
        });
    }

    /**
     * download
     * @param string $resource
     * @param string|null $filename
     * @param string|null $savePath
     * @return ResponseCollection
     * @throws BindingResolutionException|CommonException|InvalidISPException
     */
    public function download(string $resource, ?string $filename, ?string $savePath)
    {
        $response = $this->getMaterial($resource);

        if ($response->getStatusCode() === 200) {
            $filePath = File::saveFileFromResponse($response, $resource, $savePath, $filename);

            return $this->returnCollect($response, function (ResponseCollection $collect, ResponseInterface $response) use ($filePath) {
                $collect->setStatusCode($response->getStatusCode())
                    ->setResult(true)
                    ->set('file_path', $filePath);
            });
        } else {
            return $this->returnCollect($response, [$this, 'downloadFail']);
        }
    }
}