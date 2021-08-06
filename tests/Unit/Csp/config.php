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
        'cspid' => 'test12345',
        'cspToken' => '123456',
        'serverRoot' => 'http://81.68.104.218:8855',
        'fileServerRoot' => 'http://81.68.104.218:8855',
    ],
    Const5G::CU => [
        'cspId' => 'cspId-CU',
        'accessKey' => 'accessKey-CU',
        'serverRoot' => 'http://81.68.104.218:8855',
        'apiVersion' => 'v1.0',
    ],
    Const5G::CT => [
        'cspId' => 'cspId-CU',
        'accessKey' => 'accessKey-CU',
        'serverRoot' => 'http://81.68.104.218:8855',
        'apiVersion' => 'v1.0',
    ]
];