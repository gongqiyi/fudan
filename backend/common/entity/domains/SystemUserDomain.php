<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%system_user}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property integer $status
 * @property string $realname
 * @property string $email
 * @property string $create_time
 * @property string $last_login_time
 * @property string $last_login_ip
 */
class SystemUserDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'trim'],
            [['username', 'password'], 'required'],
            [['status', 'create_time','last_login_ip'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['password'], 'filter','filter'=>function($value){
                return Yii::$app->getSecurity()->generatePasswordHash($value);
            }],
            [['realname'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 80],
            [['email'],'email'],
            [['username'], 'unique'],
            [['last_login_time'], 'string', 'max' => 15],
            [['create_time'],'default','value'=>time()]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '登陆密码',
            'status' => '用户状态',
            'realname' => '真实姓名',
            'email' => '电子邮箱',
            'create_time' => '创建时间',
            'last_login_time' => '上传登录时间',
            'last_login_ip' => '上次登录Ip',
        ];
    }
}
