<?php
/**
 * User: zhouhua
 * Date: 2021/6/28
 * Time: 5:28 下午
 */

namespace Easy5G;

use Easy5G\Kernel\App;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Chatbot\Application;

/**
 * Class Factory
 * @package Easy5G
 *
 * @method static Application Chatbot(array $config = [], $singleton = true)
 */
class Factory
{
    /**
     * make 创建应用
     * @param $name
     * @param array $config
     * @param bool $singleton
     * @return App|mixed
     * @throws InvalidConfigException
     */
    public static function make($name, array $config = [], bool $singleton = true)
    {
        $namespace = ucfirst(strtolower($name));

        /** @var App $application */
        $application = "\\Easy5G\\{$namespace}\\Application";

        if ((!$singleton || !$application::hasInstance()) && empty($config)) {
            throw new InvalidConfigException('Configuration cannot be empty');
        }

        if ($singleton) {
            $app = $application::getInstance();
        } else {
            $app = new $application;
        }

        if (!empty($config)) {
            $app->registerConfigRepository($config);
        }

        return $app;
    }

    /**
     * __callStatic 创建应用
     * @param $name
     * @param $arguments
     * @return App|mixed
     * @throws InvalidConfigException
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
