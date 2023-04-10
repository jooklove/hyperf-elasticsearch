<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.plus
 *
 * @link     https://www.hyperf.plus
 * @document https://doc.hyperf.plus
 * @contact  4213509@qq.com
 * @license  https://github.com/hyperf-plus/admin/blob/master/LICENSE
 */
namespace World\HyperfElasticsearch;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
                'ignore_annotations' => [
                    'mixin',
                ],
            ],
            'publish' => [
                [
                    'id' => 'elasticsearch',
                    'description' => 'elasticsearch',
                    'source' => __DIR__ . '/../publish/elasticsearch.php',
                    'destination' => BASE_PATH . '/config/autoload/elasticsearch.php',
                ],
            ],
        ];
    }
}
