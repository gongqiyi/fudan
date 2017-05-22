<?php
// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/4.
// +----------------------------------------------------------------------

/**
 * LoginAsset.php
 */

namespace manage\assets;


use yii\web\AssetBundle;

class UeditorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [];

    public $js = [
        'js/plugins/ueditor/ueditor.config.js',
        'js/plugins/ueditor/ueditor.all.min.js',
    ];

    public $depends = [];

}