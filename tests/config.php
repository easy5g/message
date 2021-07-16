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
        'cspToken' => 'ff556388699c84a4ae73bc719eda6480c0d8290c575f297d7f65dad8f9c6804f',
        'cspid' => 'test12345',
        'apiVersion' => 'v1.0',
        'chatbotId' => 'sip:x@163.com',
        'serverRoot' => 'http://81.68.104.218:8855',
        'userId' => '123sad'
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