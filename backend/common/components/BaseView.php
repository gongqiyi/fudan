<?php
// +----------------------------------------------------------------------
// | forgetwork
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/5/23.
// +----------------------------------------------------------------------

/**
 * 视图基类
 */

namespace common\components;


class BaseView extends \yii\web\View
{

    /**
     * @var string seo信息
     */
    public $title;
    public $keywords;
    public $description;

    /**
     * 根据对象返回一个类名（不包含命名空间）
     * @param $object
     * @return mixed
     */
    public function getClassName($object){
        $tem = explode('\\',get_class($object));
        return $tem[count($tem)-1];
    }
}