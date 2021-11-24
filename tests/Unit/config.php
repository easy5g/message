<?php
/**
 * User: zhouhua
 * Date: 2021/7/2
 * Time: 11:40 上午
 */

use Easy5G\Kernel\Support\Const5G;

return [
    \Easy5G\Chatbot\Config::TYPE => [
        Const5G::CM => [
            'appId' => 'test12345',
            'password' => '123456',
            'chatbotURI' => 'sip:x@163.com',
            'serverRoot' => 'http://127.0.0.1:8855',
            'fileServerRoot' => 'http://127.0.0.1:8855',
        ],
        Const5G::CU => [
            'appId' => 'appId-CU',
            'appKey' => 'appKey-CU',
            'apiVersion' => 'v1.0',
            'chatbotId' => 'sip:x@163.com',
            'serverRoot' => 'http://127.0.0.1:8855'
        ],
        Const5G::CT => [
            'appId' => 'appId-CT',
            'appKey' => 'appKey-CT',
            'apiVersion' => 'v1.0',
            'chatbotId' => 'sip:x@163.com',
            'serverRoot' => 'http://127.0.0.1:8855'
        ]
    ],
    \Easy5G\Csp\Config::TYPE => [
        Const5G::CM => [
            'cspid' => 'test12345',
            'cspToken' => '123456',
            'serverRoot' => 'http://127.0.0.1:8855',
            'fileServerRoot' => 'http://127.0.0.1:8855',
        ],
        Const5G::CU => [
            'cspId' => 'cspId-CU',
            'accessKey' => 'accessKey-CU',
            'serverRoot' => 'http://127.0.0.1:8855',
            'apiVersion' => 'v1.0',
        ],
        Const5G::CT => [
            'cspId' => 'cspId-CU',
            'accessKey' => 'accessKey-CU',
            'serverRoot' => 'http://127.0.0.1:8855',
            'apiVersion' => 'v1.0',
        ]
    ]
];