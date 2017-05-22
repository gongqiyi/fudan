<?php

namespace common\entity\models;

use Yii;

/**
 * This is the model class for table "{{%system_log}}".
 *
 */
class SystemLogModel extends \common\entity\domains\SystemLogDomain
{
    /**
     * 记录操作日志
     * @param string $type
     * @param string $content
     * @param null $siteName
     */
    public static function create($type,$content,$siteName = null){
        return true;

        $model = new self();

        $siteInfo = Yii::$app->getSession()->get('siteInfo');
        $model->site_name = $siteName===null?$siteInfo['title']:$siteName;

        $model->operation_type = $type;
        $model->content = $content;
        $model->create_time  = time();

        $userInfo = Yii::$app->getSession()->get('userInfo');

        $model->crate_user = $userInfo['username'];

        $model->save();
    }

}
