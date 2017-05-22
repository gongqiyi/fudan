<?php
// +----------------------------------------------------------------------
// | dookay
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/7/11.
// +----------------------------------------------------------------------

/**
 * 小于ie9
 */

namespace home\assets;


use yii\web\AssetBundle;

class Ie9Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD,'condition' => 'lte IE9'];

    /* 开始 */
    public $css = [];

    public $js = [
        'js/html5shiv.js',
        'js/respond.min.js'
    ];
    /* 结束 */

    public $depends = [];

}