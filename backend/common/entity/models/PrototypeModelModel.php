<?php

namespace common\entity\models;

use common\entity\domains\PrototypeModelDomain;
use common\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "{{%prototype_model}}".
 */
class PrototypeModelModel extends PrototypeModelDomain
{

    /**
     * 查询模型列表
     * @param null $modelId
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    static public function findModel($modelId = null){
        $category = Yii::$app->cache->get('model');
        if(!$category){
            $category = self::find()->indexBy('id')->orderBy(['id'=>SORT_ASC])->asArray()->all();
            Yii::$app->cache->set('model',$category,1800);
        }

        if($modelId !== null){
            if(array_key_exists($modelId,$category)){
                return ArrayHelper::convertToObject($category[$modelId]);
            }else{
                return null;
            }
        }

        return $category;
    }
}
