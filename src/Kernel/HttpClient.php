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
     * @param $url
     * @param array $options
     * @return string
     * @throws BadRequestException
     */
    public function post($url, $options = [])
    {
        $options['timeout'] = 2;

        try {
            $response = $this->getClient()->request('POST', $url, $options);
        } catch (GuzzleException $e) {
            throw new BadRequestException($e->getMessage());
        }

        $httpStatusCode = $response->getStatusCode();

        if ($httpStatusCode !== 200) {
            throw new BadRequestException('POST request to ' . $url . ' return ' . $httpStatusCode . ' HTTP Status Code');
        }

        return $response->getBody()->getContents();
    }

    /**
     * get
     * @param $url
     * @param array $options
     * @return string
     * throws BadRequestException
     */
    public function get($url, $options = [])
    {
        $options['timeout'] = 2;

        try {
            $response = $this->getClient()->request('GET', $url, $options);
        } catch (GuzzleException $e) {
            throw new BadRequestException($e->getMessage());
        }

        $httpStatusCode = $response->getStatusCode();

        if ($httpStatusCode !== 200) {
            throw new BadRequestException('POST request to ' . $url . ' return ' . $httpStatusCode . ' HTTP Status Code');
        }

        return $response->getBody()->getContents();
    }
}