<?php
/**
 * User: zhouhua
 * Date: 2021/6/30
 * Time: 5:49 下午
 */

namespace Easy5G\Maap\Base;


use Easy5G\Kernel\ISPSelector;
use Easy5G\Kernel\Support\Const5G;

class Selector extends ISPSelector
{
    public $serviceMap = [
        Const5G::CM => ChinaMobile::class,
        Const5G::CU => ChinaUnicom::class,
        Const5G::CT => ChinaTelecom::class,
    ];
}