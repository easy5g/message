<?php
/**
 * User: zhouhua
 * Date: 2021/7/23
 * Time: 5:31 下午
 */

namespace Easy5G\Kernel\Support;


use Psr\Http\Message\ResponseInterface;

class ResponseCollection extends Collection
{
    public $statusCode;
    public $result = false;
    public $code;
    public $message = '';
    public $raw = '';

    /**
     * parseResponse
     * @param ResponseInterface $response
     * @return ResponseCollection
     */
    public function parseResponse(ResponseInterface $response)
    {
        $this->setStatusCode($response->getStatusCode());

        $this->setRaw($response->getBody()->getContents());

        return $this;
    }

    /**
     * setStatusCode
     * @param int $status
     * @return ResponseCollection
     */
    public function setStatusCode(int $status)
    {
        $this->statusCode = $status;

        return $this;
    }

    /**
     * setRaw
     * @param string $raw
     * @return ResponseCollection
     */
    public function setRaw(string $raw)
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * setResult
     * @param bool $result
     * @return ResponseCollection
     */
    public function setResult(bool $result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * setCode
     * @param $code
     * @return ResponseCollection
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * setMessage
     * @param $message
     * @return ResponseCollection
     */
    public function setMessage($message)
    {
        if (!empty($message)) {
            $this->message = $message;
        }

        return $this;
    }

    /**
     * getStatusCode
     * @return int|null
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * getResult
     * @return bool
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * getCode
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * getMessage
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * getRaw
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }
}