<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%system_role}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property integer $status
 * @property string $remark
 */
class SystemRoleDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'status'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'name' => '角色名称',
            'status' => '角色状态',
            'remark' => '角色描述',
        ];
    }

}
