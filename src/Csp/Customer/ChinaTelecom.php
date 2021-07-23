<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:02 上午
 */

namespace Easy5G\Csp\Customer;


use Easy5G\Kernel\Support\Const5G;

class ChinaTelecom extends Client
{
    use Common;

    protected $uploadUrl = '%s/cspApi/%s/uploadFile';
    protected $tokenUrl = '%s/cspApi/%s/getFile';

    protected $serviceProvider = Const5G::CT;
}