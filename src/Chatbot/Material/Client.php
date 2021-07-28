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
use Easy5G\Kernel\Support\ResponseCollection;
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
     * uploadResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    abstract protected function uploadResponse(ResponseCollection $collect, ResponseInterface $response);

    /**
     * deleteResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     * @return mixed
     */
    abstract protected function deleteResponse(ResponseCollection $collect, ResponseInterface $response);

    /**
     * getMultipart
     * @param string $path
     * @param string|null $thumbnailPath
     * @return array
     */
    abstract protected function getMultipart(string $path, ?string $thumbnailPath = null): array;

    /**
     * downloadFail
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    abstract protected function downloadFail(ResponseCollection $collect, ResponseInterface $response);

    /**
     * upload
     * @param string $path
     * @param string|null $thumbnailPath
     * @return ResponseCollection
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

        return $this->returnCollect($response, [$this, 'uploadResponse']);
    }

    /**
     * delete
     * @param string $media
     * @return ResponseCollection
     * @throws InvalidConfigException
     */
    public function delete(string $media)
    {
        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->delete($this->getCurrentUrl('delete'), [
            'headers' => $this->getDeleteHeaders($media)
        ], [200, 204]);

        return $this->returnCollect($response,[$this,'deleteResponse']);
    }

    /**
     * download
     * @param string $resource
     * @param string|null $filename
     * @param string $savePath
     * @return ResponseCollection
     * @throws CommonException
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

    /**
     * getPeriodOfValidity
     * @return string
     */
    public function getPeriodOfValidity()
    {
        return $this->periodOfValidity;
    }
}