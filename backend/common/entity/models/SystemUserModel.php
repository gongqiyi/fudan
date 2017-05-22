<?php

namespace common\entity\models;

use common\entity\domains\SystemUserDomain;
use Yii;

/**
 * This is the model class for table "{{%system_User}}".
 *
 */
class SystemUserModel extends SystemUserDomain
{

    /**
     * 获取当前用户所属角色
     * @param $userId
     * @return array
     */
    public function getUserRoles($userId){
        return SystemRoleUserRelationModel::find()->where(['user_id'=>$userId])->all();
    }

    /**
     * 设置当前用户所属角色
     * @param $userId
     * @param $userRoles
     * @return array
     */
    public function setUserRoles($userId,$userRoles){
        $this->delUserRoles($userId);

        $newData = [];
        foreach($userRoles as $item) {
            $temp['user_id'] = $userId;
            $temp['role_id'] = $item;
            $newData[] = $temp;
        }
        return Yii::$app->db->createCommand()->batchInsert(SystemRoleUserRelationModel::tableName(), ['user_id', 'role_id'], $newData)->execute();
    }

    /**
     * 删除用户角色
     * @param $userId
     * @return int
     */
    public function delUserRoles($userId){
        return SystemRoleUserRelationModel::deleteAll(['user_id'=>$userId]);
    }

    /**
     * 关联用户角色
     * @return $this
     */
    public function getRoles()
    {
        return $this->hasMany(SystemRoleModel::className(), ['id' => 'role_id'])
            ->viaTable(SystemRoleUserRelationModel::tableName(), ['user_id' => 'id']);
    }
}
