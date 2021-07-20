<?php
/**
 * User: zhouhua
 * Date: 2021/7/8
 * Time: 11:39 上午
 */

use Easy5G\Kernel\Support\Const5G;

return [
    'CM' => [
        'appid' => 'test12345',
        'password' => '123456',
        'chatbotURI' => 'sip:x@163.com',
        'serverRoot' => 'http://81.68.104.218:8855',
        'userId' => '123sad',
    ],
    'cache' => [
        'default' => 'dev',
        'dev' => [
            'driver' => 'filesystem',
            'name' => 'easy5G',
            'path' => '/tmp/'
        ],
        'product' => [
            'driver' => 'apcu',
            'name' => 'test'
        ],
    ]
];