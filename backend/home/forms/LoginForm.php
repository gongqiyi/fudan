<?php
/**
 * @copyright Copyright (c) 2016 上海稻壳网络科技有限公司
 * @link http://www.dookay.com/
 * @create Created on 2016/11/23
 */

namespace home\forms;
use common\components\BaseModel;
use common\entity\models\UserModel;
use Yii;


/**
 * 登陆
 *
 * @author xiaopig <xiaopig123456@qq.com>
 * @since 1.0
 */
class LoginForm extends BaseModel
{
    public $username;
    public $password;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password','captcha'], 'required'],
            ['password', 'string', 'min' => 6],
            ['captcha','captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'captcha'=>'验证码',
        ];
    }

    /**
     * 登录
     * @return array|bool|null|\yii\db\ActiveRecord
     */
    public function signIn(){
        if(!$this->validate()) return false;

        $userModel = UserModel::findByUsername($this->username);

        if($userModel && Yii::$app->security->validatePassword($this->password,$userModel->password)){
            Yii::$app->getUser()->login($userModel,7*24*3600);
            return $userModel;
        }

        $this->addError('username','用户名或密码错误。');
        return false;
    }
}