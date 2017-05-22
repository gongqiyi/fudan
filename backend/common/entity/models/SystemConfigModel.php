<?php

namespace common\entity\models;

use common\entity\domains\SystemConfigDomain;
use Yii;

/**
 * This is the model class for table "{{%system_config}}".
 *
 */
class SystemConfigModel extends SystemConfigDomain
{

    /**
     * 查询系统配置数据
     * @return array|mixed
     */
    static public function findConfig()
    {
        $config = Yii::$app->cache->get('config');
        if(!$config){
            $config = [];
            foreach (self::find()->asArray()->all() as $key => $value) {
                $config[$value['scope']][$value['name']] = $value['value'];
            }
            Yii::$app->cache->set('config',$config);
        }

        return $config;
    }

}
