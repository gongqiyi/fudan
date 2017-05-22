<?php
// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/4.
// +----------------------------------------------------------------------

/**
 * 登陆表单
 */

namespace manage\models;


use common\entity\models\SystemLogModel;
use common\entity\models\SystemUserModel;
use manage\libs\Rbac;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;

    private $_userInfo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password'], 'filter', 'filter' => 'trim'],
            [['username','password','verifyCode'], 'required'],
            ['password', 'validatePassword'],
            ['verifyCode', 'captcha']
        ];
    }

    /**
     * 验证密码
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()) {
            $this->_userInfo = $user = SystemUserModel::find()->where(['username'=>$this->username])->one();
            if (!$user || !Yii::$app->security->validatePassword($this->password,$user->password)) {
                $this->addError($attribute, '用户名或密码错误');
            }
        }
    }

    /**
     * tag
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' =>'用户名',
            'password' =>'密码',
            'verifyCode' =>'验证码',
        ];
    }

    /**
     * 登陆
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            $session = Yii::$app->session;

            // 判断是否超级管理员
            $params = Yii::$app->params;
            if($this->_userInfo->username == $params['SUPER_ADMIN_NAME']){
                $session->set($params['ADMIN_AUTH_KEY'],true);
            }

            // 记录登陆信息
            /*$this->_userInfo->last_login_time = time();
            $this->_userInfo->last_login_ip = Yii::$app->request->userIP;
            $this->_userInfo->save();*/

            // 设置用户权限到session
            Rbac::saveAccessList($this->_userInfo->id);

            // 设置用户信息userInfo到session中
            $userInfo = ArrayHelper::toArray($this->_userInfo);
            unset($userInfo['password']);
            $session->set('userInfo',$userInfo);

            SystemLogModel::create('login','登陆IP为“'.Yii::$app->request->userIP.'”','0');

            return true;
        }
        return false;
    }
}