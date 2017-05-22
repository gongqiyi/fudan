<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%slide_category}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $sort
 * @property string $thumb_size
 * @property integer $enable_mobile
 * @property integer $site_id
 *
 */
class SlideCategoryDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slide_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort', 'enable_mobile','site_id'], 'integer'],
            [['title', 'thumb_size'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'sort' => 'Sort',
            'thumb_size' => '生成缩略图尺寸',
            'enable_mobile' => '独立上传移动端图片',
            'site_id'=>'站点ID'
        ];
    }
}
