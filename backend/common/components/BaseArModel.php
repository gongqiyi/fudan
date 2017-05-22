<?php
// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/7.
// +----------------------------------------------------------------------

/**
 * 模型基类
 */

namespace common\components;


class BaseArModel  extends \yii\db\ActiveRecord
{
    /**
     * 根据对象返回一个类名（不包含命名空间）
     * @param $object
     * @return mixed
     */
    public function getClassName($object){
        $tem = explode('\\',get_class($object));
        return $tem[count($tem)-1];
    }

    /**
     * 表单错误处理
     * @param $error
     * @return string
     */
    public function getErrorString($error = null){
        if(!$error) $error = $this->getErrors();
        $message = '';
        if(is_string($error)){
            $message = $error;
        }else{
            foreach ($error as $item){
                if(is_array($item)){
                    foreach ($item as $v){
                        $message .= $v;
                    }
                }else{
                    $message .= $item;
                }
            }
        }
        return $message;
    }
}