<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Chatbot\Material;


use Easy5G\Kernel\BaseClient;
use Easy5G\Kernel\Exceptions\CommonException;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\File;
use Easy5G\Chatbot\Application;
use Psr\Http\Message\ResponseInterface;

abstract class Client extends BaseClient
{
    public $periodOfValidity = 'perm';

    /**
     * checkPath
     * @param string $path
     * @param string|null $thumbnailPath
     * @return array
     */
    abstract protected function checkPath(string $path, ?string $thumbnailPath = null): array;

    /**
     * getUploadHeaders
     * @return array
     */
    abstract protected function getUploadHeaders(): array;

    /**
     * getDeleteHeaders
     * @param string $media
     * @return array
     */
    abstract protected function getDeleteHeaders(string $media): array;

    /**
     * getMaterial
     * @param string $resource
     * @return ResponseInterface|string
     */
    abstract protected function getMaterial(string $resource);

    /**
     * getMultipart
     * @param string $path
     * @param string|null $thumbnailPath
     * @return array
     */
    abstract protected function getMultipart(string $path, ?string $thumbnailPath = null): array;

    /**
     * upload
     * @param string $path
     * @param string|null $thumbnailPath
     * @return string
     * @throws CommonException|InvalidConfigException
     */
    public function upload(string $path, ?string $thumbnailPath = null)
    {
        if (!File::readable($path)) {
            throw new CommonException('Unable to get file:' . $path);
        }

        if (!empty($thumbnailPath) && !File::readable($thumbnailPath)) {
            throw new CommonException('Unable to get file:' . $thumbnailPath);
        }

        $multipart = $this->getMultipart($path, $thumbnailPath);

        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->post($this->getCurrentUrl('upload'), [
            'multipart' => $multipart,
            'headers' => $this->getUploadHeaders()
        ]);

        foreach ($multipart as $fileInfo) {
            fclose($fileInfo['content']);
        }

        return $response;
    }

    /**
     * delete
     * @param string $media
     * @return string
     * @throws InvalidConfigException
     */
    public function delete(string $media)
    {
        /** @var Application $app */
        $app = $this->app;

        return $app->httpClient->delete($this->getCurrentUrl('delete'), [
            'headers' => $this->getDeleteHeaders($media)
        ]);
    }

    /**
     * download
     * @param string $resource
     * @param string|null $filename
     * @param string $savePath
     * @return bool|string
     * @throws CommonException
     */
    public function download(string $resource, ?string $filename, ?string $savePath)
    {
        $response = $this->getMaterial($resource);

        if ($response instanceof ResponseInterface) {
            return File::saveFileFromResponse($response, $resource, $savePath, $filename);
        }

        return $response;
    }

    /**
     * getPeriodOfValidity
     * @return string
     */
    public function getPeriodOfValidity()
    {
        return $this->periodOfValidity;
    }
}