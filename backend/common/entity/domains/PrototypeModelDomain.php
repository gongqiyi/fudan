<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%prototype_model}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $name
 * @property integer $type
 * @property integer $is_login
 * @property string $description
 * @property string $route
 */
class PrototypeModelDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prototype_model}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'name'], 'required'],
            [['type', 'is_login'], 'integer'],
            [['title', 'description'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 60],
            [['route'], 'string', 'max' => 30],
            [['name'], 'filter','filter'=>function($value){
                return strtolower($value);
            }],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '模型标题',
            'name' => '模型名称',
            'type' => '模型类型',
            'is_login' => '是否系统内置',
            'description' => '模型描述',
            'route' => '路由',
        ];
    }
}
