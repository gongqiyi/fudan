<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%slide}}".
 *
 * @property string $id
 * @property integer $category_id
 * @property string $title
 * @property string $thumb
 * @property string $thumb_mobile
 * @property integer $related_data_model
 * @property string $related_data_id
 * @property string $link
 * @property string $sort
 * @property integer $status
 * @property string $description
 * @property string $create_time
 * @property integer $site_id
 *
 */
class SlideDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'title','site_id'], 'required'],
            [['category_id', 'related_data_model', 'related_data_id', 'sort', 'status', 'create_time','site_id'], 'integer'],
            [['title'], 'string', 'max' => 100],
            ['link','required','on'=>'linkType'],
            [['thumb', 'thumb_mobile'], 'string', 'max' => 1000],
            [['link', 'description'], 'string', 'max' => 255],
            ['create_time','default','value'=>time()]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_id'=>'Site Id',
            'category_id' => 'Category ID',
            'title' => '标题',
            'thumb' => '图片',
            'thumb_mobile' => '移动端图片',
            'related_data_model' => '所属栏目',
            'related_data_id' => '关联数据ID',
            'link' => '链接',
            'description'=>'描述',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => 'Create Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryInfo(){
        return $this->hasOne(SlideCategoryDomain::className(),['id'=>'category_id']);
    }
}
