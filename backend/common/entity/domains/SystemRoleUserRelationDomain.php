<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%system_role_user_relation}}".
 *
 * @property integer $role_id
 * @property string $user_id
 */
class SystemRoleUserRelationDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_role_user_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'user_id'], 'required'],
            [['role_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'user_id' => 'User ID',
        ];
    }
}
