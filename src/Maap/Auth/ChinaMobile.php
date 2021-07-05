<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Maap\Auth;


use Easy5G\Kernel\Support\Const5G;

class ChinaMobile extends Client
{
    use Common;

    const TOKEN_URL = 'https://%s/bot/%s/%s/accessToken';

    protected $thirdUrl;
    protected $serviceProvider = Const5G::CM;
}