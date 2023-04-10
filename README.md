## Elasticsearch client for hyperf

#### 
- 1、安装
```bash
    composer require wo_orld/hyperf-elasticsearch
```

- 2、生成elasticsearch配置文件
```bash
    php bin/hyperf.php vendor:publish wo_orld/hyperf-elasticsearch
```

- 3、开始使用
```php
<?php

use World\HyperfElasticsearch\ElasticsearchClientBuilderFactory;

// 如果在协程环境下创建，则会自动使用协程版的 Handler，非协程环境下无改变
$builder = $this->container->get(ElasticsearchClientBuilderFactory::class)->create();

$client = $builder->build();

$info = $client->info();
```