<?php
/**
 * User: zhouhua
 * Date: 2021/7/2
 * Time: 10:00 上午
 */

namespace Easy5G\Kernel;


use Easy5G\Kernel\Contracts\HttpClientInterface;
use Easy5G\Kernel\Exceptions\BadRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    /** @var Client */
    protected $client;

    /**
     * getClient
     * @return Client
     */
    public function getClient()
    {
        if (!isset($this->client)) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * post
     * @param string $url
     * @param array $options
     * @param int[] $acceptStatusCode
     * @return ResponseInterface
     */
    public function post($url, $options = [], $acceptStatusCode = [200])
    {
        $options['timeout'] = 2;

        return $this->request('POST', $url, $options, $acceptStatusCode);
    }

    /**
     * get
     * @param string $url
     * @param array $options
     * @param int[] $acceptStatusCode
     * @return ResponseInterface
     */
    public function get($url, $options = [], $acceptStatusCode = [200])
    {
        $options['timeout'] = 2;

        return $this->request('GET', $url, $options, $acceptStatusCode);
    }

    /**
     * delete
     * @param $url
     * @param array $options
     * @param int[] $acceptStatusCode
     * @return ResponseInterface
     */
    public function delete($url, $options = [], $acceptStatusCode = [200])
    {
        $options['timeout'] = 2;

        return $this->request('DELETE', $url, $options, $acceptStatusCode);
    }

    /**
     * request
     * @param $method
     * @param $url
     * @param $options
     * @param int[] $acceptStatusCode
     * @return ResponseInterface
     */
    public function request($method, $url, $options, $acceptStatusCode = [200])
    {
        try {
            $response = $this->getClient()->request($method, $url, $options);
        } catch (GuzzleException $e) {
            throw new BadRequestException($e->getMessage());
        }

        $httpStatusCode = $response->getStatusCode();

        if (!in_array($httpStatusCode, $acceptStatusCode)) {
            throw new BadRequestException($method . ' request to ' . $url . ' return ' . $httpStatusCode . ' HTTP Status Code', $httpStatusCode);
        }

        return $response;
    }
}