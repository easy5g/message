<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Chatbot\Material;


use Easy5G\Kernel\Exceptions\CommonException;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Response;

class Selector extends ISPSelector
{
    public $serviceMap = [
        Const5G::CM => ChinaMobile::class,
        Const5G::CU => ChinaUnicom::class,
        Const5G::CT => ChinaTelecom::class,
    ];

    /**
     * uploadImage
     * @param string $path
     * @param string|null $thumbnailPath
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|CommonException|InvalidISPException|InvalidConfigException
     */
    public function uploadImage(string $path, ?string $thumbnailPath = null, ?string $ISP = null, ?string $url = null)
    {
        return $this->upload(...func_get_args());
    }

    /**
     * uploadVoice
     * @param string $path
     * @param string|null $thumbnailPath
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|CommonException|InvalidISPException|InvalidConfigException
     */
    public function uploadVoice(string $path, ?string $thumbnailPath = null, ?string $ISP = null, ?string $url = null)
    {
        return $this->upload(...func_get_args());
    }

    /**
     * uploadVideo
     * @param string $path
     * @param string|null $thumbnailPath
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|CommonException|InvalidISPException|InvalidConfigException
     */
    public function uploadVideo(string $path, ?string $thumbnailPath = null, ?string $ISP = null, ?string $url = null)
    {
        return $this->upload(...func_get_args());
    }

    /**
     * upload
     * @param string $path
     * @param string|null $thumbnailPath
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|CommonException|InvalidConfigException|InvalidISPException
     */
    public function upload(string $path, ?string $thumbnailPath = null, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($url) {
            $client->setThirdUrl($url, 'upload');
        }

        if ($client->getPeriodOfValidity() === 'temp' && $client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this way of Material upload');
        }

        return $client->upload($path, $thumbnailPath);
    }

    /**
     * delete
     * @param string $media
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException
     */
    public function delete(string $media, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($url) {
            $client->setThirdUrl($url, 'delete');
        }

        return $client->delete($media);
    }

    /**
     * download
     * @param string $resource
     * @param string|null $filename
     * @param string|null $savePath
     * @param string|null $ISP
     * @param string|null $url
     * @return ResponseCollection
     * @throws BindingResolutionException|InvalidISPException|CommonException
     */
    public function download(string $resource, ?string $filename = null, ?string $savePath = null, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($client instanceof ChinaMobile) {
            $client->setThirdUrl($resource, 'download');
        }

        if ($url) {
            $client->setThirdUrl($url, 'download');
        }

        return $client->download($resource, $filename, $savePath);
    }

    /**
     * notify
     * @param callable|null $callback
     * @param string|null $ISP
     * @return Response
     * @throws BindingResolutionException|InvalidISPException
     */
    public function notify(?callable $callback = null, ?string $ISP = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        return $client->notify($callback);
    }
}
