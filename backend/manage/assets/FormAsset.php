<?php
// +----------------------------------------------------------------------
// | SimplePig
// +----------------------------------------------------------------------
// | Copyright (c) 2016-+ http://www.zhuyanjun.cn.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/3/13 21:49.
// +----------------------------------------------------------------------

/**
 * 公用资源包
 */

namespace manage\assets;

use yii\web\AssetBundle;


class FormAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        'js/common.form.js',
    ];

    public $depends = [
        'manage\assets\CommonAsset',
    ];

}
