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
     * @param bool $responseInstance
     * @return ResponseInterface|string
     */
    public function post($url, $options = [], $responseInstance = false)
    {
        $options['timeout'] = 2;

        return $this->request('POST', $url, $options, $responseInstance);
    }

    /**
     * get
     * @param string $url
     * @param array $options
     * @param bool $responseInstance
     * @return ResponseInterface|string
     */
    public function get($url, $options = [], $responseInstance = false)
    {
        $options['timeout'] = 2;

        return $this->request('GET', $url, $options, $responseInstance);
    }

    /**
     * delete
     * @param $url
     * @param array $options
     * @param bool $responseInstance
     * @return ResponseInterface|string
     */
    public function delete($url, $options = [], $responseInstance = false)
    {
        $options['timeout'] = 2;

        return $this->request('DELETE', $url, $options, $responseInstance);
    }

    /**
     * request
     * @param $method
     * @param $url
     * @param $options
     * @param bool $responseInstance
     * @return ResponseInterface|string
     */
    public function request($method, $url, $options, $responseInstance = false)
    {
        try {
            $response = $this->getClient()->request($method, $url, $options);
        } catch (GuzzleException $e) {
            throw new BadRequestException($e->getMessage());
        }

        if ($responseInstance) {
            return $response;
        }

        $httpStatusCode = $response->getStatusCode();

        if ($httpStatusCode !== 200) {
            throw new BadRequestException($method . ' request to ' . $url . ' return ' . $httpStatusCode . ' HTTP Status Code', $httpStatusCode);
        }

        return $response->getBody()->getContents();
    }
}