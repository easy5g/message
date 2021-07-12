<?php
/**
 * User: zhouhua
 * Date: 2021/7/8
 * Time: 11:39 上午
 */

use Easy5G\Kernel\Support\Const5G;

return [
    'CM' => [
        'cspToken' => 'ff556388699c84a4ae73bc719eda6480c0d8290c575f297d7f65dad8f9c6804f',
        'cspid' => 'test12345',
        'apiVersion' => 'v1.0',
        'chatbotId' => 'sip:x@163.com',
        'serverRoot' => 'http://81.68.104.218:8855'
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