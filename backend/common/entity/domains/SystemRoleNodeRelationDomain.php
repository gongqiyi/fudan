<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%system_role_node_relation}}".
 *
 * @property integer $type
 * @property integer $role_id
 * @property integer $node_id
 * @property integer $level
 */
class SystemRoleNodeRelationDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_role_node_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'role_id', 'node_id', 'level'], 'integer'],
            [['level'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'role_id' => 'Role ID',
            'node_id' => 'Node ID',
            'level' => 'Level',
        ];
    }

}
