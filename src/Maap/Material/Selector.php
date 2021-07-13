<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Maap\Material;


use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;

class Selector extends ISPSelector
{
    public $serviceMap = [
        Const5G::CM => ChinaMobile::class,
        Const5G::CU => ChinaUnicom::class,
        Const5G::CT => ChinaTelecom::class,
    ];

    public function uploadImage(string $path, ?string $ISP = null, ?string $url = null)
    {
        return $this->upload(...func_get_args());
    }

    public function uploadVoice(string $path, ?string $ISP = null, ?string $url = null)
    {
        return $this->upload(...func_get_args());
    }

    public function uploadVideo(string $path, ?string $ISP = null, ?string $url = null)
    {
        return $this->upload(...func_get_args());
    }

    public function uploadThumb(string $path, ?string $ISP = null, ?string $url = null)
    {
        return $this->upload(...func_get_args());
    }

    public function upload(string $path, ?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($url) {
            $client->setThirdUrl($url,'upload');
        }

        $client->upload($path);
    }
}
