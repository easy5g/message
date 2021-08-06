<?php
/**
 * User: zhouhua
 * Date: 2021/7/2
 * Time: 11:40 上午
 */

use Easy5G\Kernel\Support\Const5G;

return [
    //三个运营商至少必须填一个
    Const5G::CM => [
        'appId' => 'test12345',
        'password' => '123456',
        'chatbotURI' => 'sip:x@163.com',
        'serverRoot' => 'http://81.68.104.218:8855',
        'fileServerRoot' => 'http://81.68.104.218:8855',
    ],
    Const5G::CU => [
        'appId' => 'appId-CU',
        'appKey' => 'appKey-CU',
        'apiVersion' => 'v1.0',
        'chatbotId' => 'sip:x@163.com',
        'serverRoot' => 'http://81.68.104.218:8855'
    ],
    Const5G::CT => [
        'appId' => 'appId-CT',
        'appKey' => 'appKey-CT',
        'apiVersion' => 'v1.0',
        'chatbotId' => 'sip:x@163.com',
        'serverRoot' => 'http://81.68.104.218:8855'
    ]
];