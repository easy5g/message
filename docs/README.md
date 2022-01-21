# 开始使用
# 简介
5G消息平台目前分为两部分
## 快速开始

```php
    //获取access_token
    use Easy5G\Factory;
    use Easy5G\Kernel\Support\Const5G;

    $config = [
        Const5G::CHATBOT_CONFIG => [
            Const5G::CM => [
                'appId' => 'test12345',
                'password' => '123456',
                'chatbotURI' => 'sip:x@163.com',
                'serverRoot' => 'http://127.0.0.1:8855',
                'fileServerRoot' => 'http://127.0.0.1:8855',
            ]
        ]
    ];
    
    //获取app类
    $chatbot = Factory::Chatbot($config);
    
    //获取access_token
    $token = $chatbot->access_token->getToken();
```

## 常量
目前所有常量定义在[\Easy5G\Kernel\Support\Const5G](https://github.com/easy5g/message/blob/master/src/Kernel/Support/Const5G.php)

## 配置
以下为完整的配置文件
```php
use Easy5G\Kernel\Support\Const5G;

return [
    Const5G::CHATBOT_CONFIG => [
        //如要使用chatbot则移电联三种配置至少需要填写一种，该配置在运营商处获得
        //移动chatbot配置
        Const5G::CM => [
            'appId' => 'appId',
            'password' => 'password',
            'chatbotURI' => 'sip:x@163.com',
            'serverRoot' => 'http://127.0.0.1:8855',
            'userId' => '123sad',
        ],
        //联通chatbot配置
        Const5G::CU => [
            'appId' => 'appId',
            'appKey' => 'appKey',
            'apiVersion' => 'v1.0',
            'chatbotId' => 'sip:x@163.com',
            'serverRoot' => 'http://127.0.0.1:8855'
        ],
        //电信chatbot配置
        Const5G::CT => [
            'appId' => 'appId',
            'appKey' => 'appKey',
            'apiVersion' => 'v1.0',
            'chatbotId' => 'sip:x@163.com',
            'serverRoot' => 'http://127.0.0.1:8855'
        ],
    ],
    Const5G::CSP_CONFIG => [
        //如要使用csp则移电联三种配置至少需要填写一种，该配置在运营商处获得
        Const5G::CM => [
            'cspid' => 'cspid',
            'cspToken' => 'cspToken',
            'serverRoot' => 'serverRoot',
        ],
        Const5G::CU => [
            'cspId' => 'cspId',
            'accessKey' => 'accessKey',
            'serverRoot' => 'http://127.0.0.1:8855',
            'apiVersion' => 'v1.0',
        ],
        Const5G::CT => [
            'cspId' => 'cspId',
            'accessKey' => 'accessKey',
            'serverRoot' => 'http://127.0.0.1:8855',
            'apiVersion' => 'v1.0',
        ]
    ],
    //缓存配置默认使用文件缓存使用的是Symfony的Cache组件可不填写默认filesystem
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
        ]
    ]
];
```

# Chatbot
```php
$config = [
//....
];

$chatbot = \Easy5G\Factory::Chatbot($config);
```
> chatbot类通过`\Easy5G\Factory`类进行创建，函数原型`Chatbot(array $config = [], $singleton = true)`其中`$config`即为配置选项中的数组，`$singleton`为工厂类创建的是否是单列，默认是单列多次调用都是同一个chatbot类，如果需要新的chatbot类`$singleton`设置为false即可
> 当$config中只有移电联三家中一家的配置的时候，后续调用chatbot提供的方法的时候不需要传入$ISP，但如果$config中包含两家以上的配置的时候，则需要传入$ISP，详见后续接口说明

## 属性
通过`Factory`创建的`chatbot`类带有以下几个属性，可以用于访问5G平台的功能
 * access_token `Easy5G\Chatbot\Auth\Selector`
 * server `Easy5G\Chatbot\Server\Selector`
 * info `Easy5G\Chatbot\Info\Selector`
 * menu `Easy5G\Chatbot\Menu\Selector`
 * material `Easy5G\Chatbot\Material\Selector`
 * media `Easy5G\Chatbot\Material\MediaSelector`
 * template_message `Easy5G\Chatbot\TemplateMessage\Selector`
 * broadcasting `Easy5G\Chatbot\Broadcasting\Selector`

### access_token
可以通过访问chatbot的access_token属性获取相关auth方法
#### 方法
```php
$chatbot->access_token->getToken(bool $refresh = false, string $ISP = null, string $url = null) : string
```
* 参数
    * $refresh 是否强制刷新token

联通和移动的`accessToken`是通过请求各自业务平台获取的，正常情况下accessToken有效期为7200秒，并且api调用次数非常有限