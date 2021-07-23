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
use Easy5G\Kernel\Support\File;

abstract class Client extends BaseClient
{
    /**
     * getUploadHeaders
     * @param int $uploadType
     * @return array
     */
    abstract protected function getUploadHeaders(int $uploadType): array;

    /**
     * upload
     * @param string $path
     * @param int $uploadType
     * @return string
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

        return $app->httpClient->post($this->getCurrentUrl('upload'), [
            'body' => file_get_contents($path),
            'headers' => $this->getUploadHeaders($uploadType)
        ]);
    }

    public function download()
    {

    }
}