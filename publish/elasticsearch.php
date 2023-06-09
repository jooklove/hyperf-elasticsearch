<?php

return [
    // 索引前缀
    'prefix' => env('ES_PREFIX', env('APP_NAME')),

    // 对应logger.php下的配置，置空则不写日志
    'logger' => [
        'local' => 'default',
        'test' => '',
        'production' => '',
    ],

    // 节点地址,多个节点使用|分隔  例如 http://127.0.0.1:9201|http://127.0.0.1:9202
    'hosts' => env('ES_HOSTS', 'http://127.0.0.1:9200'),

    'username' => env('ES_USERNAME', 'elastic'),
    'password' => env('ES_PASSWORD', ''),
];