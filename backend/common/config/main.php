<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        // 开启缓存
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'coralNode',
        ],

        //国际化
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages'
                ],
            ],
        ],
    ],
    // 设置语言为中文
    'language'=>'zh-CN',
    'timeZone'=>'PRC',
];
