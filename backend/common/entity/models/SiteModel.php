<?php

namespace common\entity\models;

use Yii;

/**
 * This is the model class for table "{{%site}}".
 */
class SiteModel extends \common\entity\domains\SiteDomain
{
    /**
     * 查询系统配置数据
     * @return array|mixed
     */
    static public function findSite()
    {
        $site = Yii::$app->cache->get('site');
        if(!$site){
            $site = self::find()->indexBy('id')->asArray()->all();
            Yii::$app->cache->set('site',$site);
        }
        return $site;
    }
}
