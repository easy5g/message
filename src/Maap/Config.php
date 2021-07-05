<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 3:54 下午
 */

namespace Easy5G\Maap;


use Easy5G\Kernel\Config\Repository;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Const5G;

class Config extends Repository
{
    public $spBaseConfigField = [
        Const5G::CM => [
            'appId' => 'string',
            'appKey' => 'string',
            'serverRoot' => 'string',
            'apiVersion' => 'string',
            'chatbotId' => 'string'
        ],
        Const5G::CU => [
            'appId' => 'string',
            'appKey' => 'string',
            'serverRoot' => 'string',
            'apiVersion' => 'string',
            'chatbotId' => 'string'
        ],
        Const5G::CT => [
            'appId' => 'string',
            'appKey' => 'string',
            'serverRoot' => 'string',
            'apiVersion' => 'string',
            'chatbotId' => 'string'
        ]
    ];

    /**
     * checkConfig 校验配置
     * @throws InvalidConfigException
     */
    public function checkConfig()
    {
        parent::checkConfig();

        foreach ($this->config as $key => $value) {
            if (isset($this->spBaseConfigField[$key])) {
                foreach ($this->spBaseConfigField[$key] as $field => $type) {
                    if (!isset($value[$field])) {
                        throw new InvalidConfigException('Missing configuration');
                    }

                    if ($type === 'string' ? !is_string($value[$field]) : !is_numeric($value[$field])) {
                        throw new InvalidConfigException('Invalid ' . $field . ' configuration');
                    }
                }

                $uri = parse_url($value['serverRoot']);

                if ($uri === false || empty($uri['host']) || empty($uri['scheme'])) {
                    throw new InvalidConfigException('The URL needs to have scheme and host');
                }
                
                $this->config[$key]['url'] = $uri['scheme'] . '://' . $uri['host'] . (empty($uri['port']) ? '' : ':' . $uri['port']);
            }
        }
    }
}