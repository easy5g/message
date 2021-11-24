<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 3:54 下午
 */

namespace Easy5G\Chatbot;


use Easy5G\Kernel\Config\Repository;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Const5G;

class Config extends Repository
{
    const TYPE = Const5G::CONFIG_TYPE_CHATBOT;

    public $spBaseConfigField = [
        Const5G::CM => [
            'appId' => 'string',
            'password' => 'string',
            'serverRoot' => 'string',
            'chatbotURI' => 'string',
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
}