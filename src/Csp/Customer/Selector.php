<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Csp\Customer;


use Easy5G\Kernel\Exceptions\CommonException;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;
use Illuminate\Contracts\Container\BindingResolutionException;

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
     * @param $uploadType
     * @param string|null $ISP
     * @param string|null $url
     * @return string
     * @throws BindingResolutionException|CommonException|InvalidConfigException|InvalidISPException
     */
    public function uploadImage(string $path,$uploadType,?string $ISP = null, ?string $url = null)
    {
        /** @var Client $client */
        $client = $this->getClient($ISP);

        if ($url) {
            $client->setThirdUrl($url, 'upload');
        }

        if ($client instanceof ChinaMobile) {
            throw new InvalidISPException('China Mobile does not support this way of Material upload');
        }

        return $client->upload($path,$uploadType);
    }

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
}