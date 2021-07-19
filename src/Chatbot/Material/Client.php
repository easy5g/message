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
    public function download(string $resource, ?string $filename, string $savePath)
    {
        if (!is_dir($savePath) && @!mkdir($savePath, 0755, true)) {
            throw new CommonException('Failed to create folders');
        }

        $response = $this->getMaterial($resource);

        if ($response instanceof ResponseInterface) {
            //没有传入文件名，则从header头获取，未获取到则按地址md5
            if (empty($filename)) {
                if (preg_match('/filename="(?<filename>.*?)"/', $response->getHeaderLine('Content-Disposition'), $match)) {
                    $filename = $match['filename'];
                } else {
                    $filename = md5($resource);
                }
            }

            $contents = $response->getBody()->getContents();

            //没有后缀则加上后缀
            if (empty(pathinfo($filename, PATHINFO_EXTENSION))) {
                $filename .= File::getStreamExt($contents);
            }

            if (@file_put_contents($savePath . '/' . $filename, $contents) === false) {
                throw new CommonException('Failed to save file path:' . $savePath . '/' . $filename);
            }

            return true;
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