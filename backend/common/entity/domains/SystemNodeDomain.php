<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%system_node}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $level
 * @property string $title
 * @property string $name
 */
class SystemNodeDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_node}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'title', 'name'], 'required'],
            [['pid', 'level'], 'integer'],
            [['title'], 'string', 'max' => 80],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父级节点',
            'level' => '节点级别',
            'title' => '节点标题',
            'name' => '节点名称',
        ];
    }
}
