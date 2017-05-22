<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%prototype_page}}".
 *
 * @property integer $category_id
 * @property string $title
 * @property string $content
 * @property string $update_time
 */
class PrototypePageDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prototype_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'title'], 'required'],
            [['category_id', 'update_time'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'title' => 'Title',
            'content' => 'Content',
            'update_time' => 'Update Time',
        ];
    }

}
