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
 * node模型基类
 */

namespace common\components;

use common\components\BaseArModel;
use common\entity\models\CommentModel;
use common\entity\models\PrototypeCategoryModel;
use common\entity\models\PrototypeModelModel;
use common\entity\models\SiteModel;
use common\entity\models\SystemUserModel;
use common\entity\models\TagRelationModel;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BaseNodeModel extends BaseArModel
{
    /**
     * 自动填充时间
     * @return array
     */
    public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time','update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
            ],
        ];
    }

    /**
     * 栏目信息
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryInfo(){
        return $this->hasOne(PrototypeCategoryModel::className(),['id'=>'category_id']);
    }

    /**
     * 模型信息
     * @return \yii\db\ActiveQuery
     */
    public function getModelInfo(){
        return $this->hasOne(PrototypeModelModel::className(),['id'=>'model_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteInfo()
    {
        return $this->hasOne(SiteModel::className(), ['id' => 'site_id']);
    }

    /**
     * 标签
     * @return \yii\db\ActiveQuery
     */
    public function getTagRelation(){
        return $this->hasOne(TagRelationModel::className(),['model_id'=>'model_id','data_id'=>'id']);
    }

    public function getTagRelations(){
        return $this->hasMany(TagRelationModel::className(),['model_id'=>'model_id','data_id'=>'id']);
    }

    /**
     * 创建者信息
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUserInfo(){
        return $this->hasOne(SystemUserModel::className(),['id'=>'create_user_id']);
    }

    /**
     * 创建者信息
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUserInfo(){
        return $this->hasOne(SystemUserModel::className(),['id'=>'update_user_id']);
    }
}