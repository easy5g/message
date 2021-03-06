<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:45 上午
 */

namespace Easy5G\Csp\Auth;


use Easy5G\Kernel\Support\Const5G;

class ChinaUnicom extends Client
{
    use Common;

    protected $tokenUrl = '%s/cspApi/%s/accessToken';

    protected $serviceProvider = Const5G::CU;
}