<?php
/**
 * User: zhouhua
 * Date: 2021/7/2
 * Time: 10:00 上午
 */

namespace Easy5G\Kernel;


use Easy5G\Kernel\Exceptions\BadRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
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
            $response = $this->client->request('POST', $url, $options);
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
            $response = $this->client->request('GET', $url, $options);
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