<?php
/**
 * User: zhouhua
 * Date: 2021/7/12
 * Time: 2:42 下午
 */

namespace Easy5G\Kernel;


use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

abstract class BaseClient
{
    public $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * setThirdUrl
     * @param $url
     * @param string $name
     */
    public function setThirdUrl($url, $name)
    {
        $this->{$this->getThirdUrlName($name)} = $url;
    }

    /**
     * getThirdUrl
     * @param $name
     * @return mixed
     */
    public function getThirdUrl($name)
    {
        return $this->{$this->getThirdUrlName($name)} ?? null;
    }

    /**
     * getUrl
     * @param $name
     * @return mixed
     */
    public function getUrl($name)
    {
        return $this->{$name . 'Url'};
    }

    /**
     * getThirdUrlName
     * @param string $name
     * @return string
     */
    protected function getThirdUrlName(string $name)
    {
        return 'third' . ucfirst($name) . 'Url';
    }

    /**
     * getCurrentUrl
     * @param $name
     * @return string
     * @throws InvalidConfigException
     */
    public function getCurrentUrl($name)
    {
        if ($thirdUrl = $this->getThirdUrl($name)) {
            return $thirdUrl;
        }

        $app = $this->app;

        $config = $app->config->get($this->serviceProvider);

        $url = $this->getUrl($name);

        if (empty($url)) {
            throw new InvalidConfigException('The correct URL is not configured here, name:' . $name);
        }

        $placeholderNum = substr_count($url, '%s');

        if ($this->serviceProvider === Const5G::CM) {
            if ($name === 'upload') {
                $serverRoot = $config['fileServerRoot'];
            } else {
                $serverRoot = $config['serverRoot'];
            }

            if ($placeholderNum === 1) {
                return sprintf($url, $serverRoot);
            } else {
                return sprintf($url, $serverRoot, $config['chatbotURI']);
            }
        } else {
            if ($placeholderNum === 2) {
                return sprintf($url, $config['serverRoot'], $config['apiVersion']);
            } else {
                return sprintf($url, $config['serverRoot'], $config['apiVersion'], $config['chatbotId']);
            }
        }
    }

    /**
     * getCTCspVerifyHeader
     * @param $accessKey
     * @return array
     */
    public function getCTCspVerifyHeader(string $accessKey)
    {
        $timestamp = date('YmdHis');

        $nonce = $timestamp . str_pad(mt_rand(0, 99999999), 8, '0');

        $signature = md5($accessKey . $nonce . $timestamp);

        return compact('timestamp', 'nonce', 'signature');
    }

    /**
     * returnCollect
     * @param ResponseInterface $response
     * @param callable|null $callback
     * @return ResponseCollection
     */
    protected function returnCollect(ResponseInterface $response, ?callable $callback = null)
    {
        $collect = new ResponseCollection();

        if (empty($callback)) {
            $collect->parseResponse($response);
        }else{
            $callback($collect, $response);
        }

        return $collect;
    }

    /**
     * utBaseResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    protected function utBaseResponse(ResponseCollection $collect, ResponseInterface $response)
    {
        $raw = $response->getBody()->getContents();

        $data = json_decode($raw, true);

        $collect->setStatusCode($response->getStatusCode())
            ->setRaw($raw)
            ->setCode($data['errorCode'])
            ->setMessage($data['errorMessage'] ?? '');

        if ($data['errorCode'] === 0) {
            $collect->setResult(true);

            unset($data['errorCode']);
            unset($data['errorMessage']);

            foreach ($data as $key => $val) {
                $collect->set($key, $val);
            }
        } else {
            $collect->setResult(false);
        }
    }

    /**
     * mBaseResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    protected function mBaseResponse(ResponseCollection $collect, ResponseInterface $response)
    {
        $raw = $response->getBody()->getContents();

        $data = json_decode($raw, true);

        $collect->setStatusCode($response->getStatusCode())
            ->setRaw($raw)
            ->setCode($data['code'])
            ->setMessage($data['msg'] ?? '');

        if ($data['code'] === '00000') {
            $collect->setResult(true);

            unset($data['code']);
            unset($data['msg']);

            foreach ($data as $key => $val) {
                $collect->set($key, $val);
            }
        } else {
            $collect->setResult(false);
        }
    }
}