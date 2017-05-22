<?php

namespace common\entity\models;

use Yii;

/**
 * This is the model class for table "{{%prototype_fragment}}".
 *
 */
class PrototypeFragmentModel extends \common\entity\domains\PrototypeFragmentDomain
{
    /**
     * 查询系统配置数据
     * @param $siteId
     * @return array|mixed
     */
    static public function findFragment($siteId)
    {
        $fragment = Yii::$app->cache->get('fragment');
        if(!$fragment){
            $fragment = self::find()->asArray()->all();
            Yii::$app->cache->set('fragment',$fragment);
        }

        $newData = [];
        foreach ($fragment as $item) {
            if($item['site_id'] != $siteId) continue;
            $newData[$item['name']] = $item['value'];
        }

        return $newData;
    }
}
