# chatbot文档
## 开始使用
### 快速开始

```php
    //获取access_token
    use Easy5G\Factory;
    use Easy5G\Kernel\Support\Const5G;
    $config = [
        Const5G::CM => [
            'appId' => 'test12345',
            'password' => '123456',
            'chatbotURI' => 'sip:x@163.com',
            'serverRoot' => 'http://127.0.0.1:8855',
            'fileServerRoot' => 'http://127.0.0.1:8855',
        ],
    ];
    
    //获取app类
    $app = Factory::Chatbot($config);
    
    //获取access_token
    $token = $app->access_token->getToken();
```

### 常量
目前所有常量定义在[\Easy5G\Kernel\Support\Const5G](https://github.com/easy5g/message/blob/master/src/Kernel/Support/Const5G.php)文件中暂时只有三个常量CM/CU/CT分别代表移动/联通/电信

### 配置
以下为完整的配置文件
```php
use Easy5G\Kernel\Support\Const5G;

return [
    //移电联三种配置至少需要填写一种，该配置在运营商处获得
    //移动chatbot配置
    Const5G::CM => [
        'appId' => 'test12345',
        'password' => '123456',
        'chatbotURI' => 'sip:x@163.com',
        'serverRoot' => 'http://127.0.0.1:8855',
        'userId' => '123sad',
    ],
    //联通chatbot配置
    Const5G::CU => [
        'appId' => 'appId-CU',
        'appKey' => 'appKey-CU',
        'apiVersion' => 'v1.0',
        'chatbotId' => 'sip:x@163.com',
        'serverRoot' => 'http://127.0.0.1:8855'
    ],
    //电信chatbot配置
    Const5G::CT => [
        'appId' => 'appId-CT',
        'appKey' => 'appKey-CT',
        'apiVersion' => 'v1.0',
        'chatbotId' => 'sip:x@163.com',
        'serverRoot' => 'http://127.0.0.1:8855'
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
        ],
    ]
];
```

# csp文档
### 快速开始

```php
    //获取access_token
    use Easy5G\Factory;
    use Easy5G\Kernel\Support\Const5G;
    $config = [
        Const5G::CM => [
            'appId' => 'test12345',
            'password' => '123456',
            'chatbotURI' => 'sip:x@163.com',
            'serverRoot' => 'http://127.0.0.1:8855',
            'fileServerRoot' => 'http://127.0.0.1:8855',
        ],
    ];
    
    //获取app类
    $app = Factory::Csp($config);
    
    //获取access_token
    $token = $app->access_token->getToken();
```

### 常量
同chatbot常量

