<?php
/**
 * 公用资源包
 */

namespace home\assets;

use yii\web\AssetBundle;


class CommonAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    /* 开始 */
    public $css = [
        'js/plugins/jquery-bxslider/jquery.bxslider.css',
        'css/dookayui.min.css',
        'iconfont/iconfont.css',
        'css/common.css',
        'css/dx.css',
        'css/Yh.css',
    ];

    public $js = [
        'js/bootstrap.min.js',
        'js/masonry.pkgd.min.js',
        'js/plugins/layer/layer.js',
        'js/common.js',
    ];
    /* 结束 */

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
