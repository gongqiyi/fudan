<?php

namespace common\entity\models;

use common\entity\domains\SystemRoleUserRelationDomain;
use Yii;

/**
 * This is the model class for table "{{%system_role_user_relation}}".
 *
 */
class SystemRoleUserRelationModel extends SystemRoleUserRelationDomain
{

    /**
     * 为RBAC类 返回权限列表
     * @return \yii\db\ActiveQuery
     */
    public function getNodeids(){
        return $this->hasMany(SystemRoleNodeRelationModel::className(), ['role_id' => 'role_id']);
    }
}
