<?php

namespace common\entity\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 */
class UserModel extends \common\entity\domains\UserDomain implements IdentityInterface
{
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * 查找用户
     * @param $username
     * @return array|null|\yii\db\ActiveRecord
     */
    static public function findByUsername($username){
        return self::find()->where(['username' =>$username,'is_enable'=>1])->one();
    }

    /**
     * 根据给到的ID查询身份
     * @param int $id 被查询的ID
     * @return array|null|\yii\db\ActiveRecord
     */
    static public function findIdentity($id)
    {

        return self::find()->where(['id' =>$id,'is_enable'=>1])->one();
    }

    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @param null $type
     * @return array|null|\yii\db\ActiveRecord
     */
    static public function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 获取基于 cookie 登录时使用的认证密钥。
     * 认证密钥储存在 cookie 里并且将来会与服务端的版本进行比较(通过validateAuthKey方法)以确保 cookie的有效性。
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * 是基于 cookie 登录密钥的 验证的逻辑的实现。
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


}
