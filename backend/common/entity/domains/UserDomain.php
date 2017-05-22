<?php

namespace common\entity\domains;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $mobile
 * @property string $email
 * @property integer $is_enable
 * @property string $auth_key
 * @property string $create_time
 */
class UserDomain extends \common\components\BaseArModel
{
    public $confirmPassword;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_enable', 'create_time'], 'integer'],
            [['username'], 'string', 'max' => 30],
            ['username','unique'],
            [['password', 'auth_key'], 'string', 'max' => 70],
            [['mobile'], 'string', 'max' => 11],
            [['email'], 'string', 'max' => 100],
            ['mobile','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}格式错误。'],
            [['email'], 'email'],
            ['is_enable','in','range'=>[0,1]],

            ['username','required','on'=>'create'],
            [['password','confirmPassword'],'required','on'=>['create','update']],
            ['confirmPassword','compare','compareAttribute'=>'password','on'=>['create','update']],
            ['password','filter','filter'=>function($value){
                return Yii::$app->getSecurity()->generatePasswordHash($value);
            },'on'=>['create','update']],

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
            'password' => '密码',
            'mobile' => '手机号码',
            'email' => '邮箱',
            'is_enable' => '状态',
            'auth_key' => '用户的（cookie）认证密钥',
            'create_time' => '创建日期',
            'confirmPassword'=>'确认密码',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {

        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
                ],
            ],
        ];
    }
}
