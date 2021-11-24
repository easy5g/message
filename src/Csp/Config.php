<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 3:54 下午
 */

namespace Easy5G\Csp;


use Easy5G\Kernel\Config\Repository;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\Const5G;

class Config extends Repository
{
    const TYPE = Const5G::CONFIG_TYPE_CSP;

    protected $spBaseConfigField = [
        Const5G::CM => [
            'cspid' => 'string',
            'cspToken' => 'string',
            'serverRoot' => 'string',
        ],
        Const5G::CU => [
            'cspId' => 'string',
            'accessKey' => 'string',
            'serverRoot' => 'string',
            'apiVersion' => 'string',
        ],
        Const5G::CT => [
            'cspId' => 'string',
            'accessKey' => 'string',
            'serverRoot' => 'string',
            'apiVersion' => 'string',
        ]
    ];
}