<?php

namespace common\entity\nodes;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%node_feedback}}".
 *
 * @property string $id
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property integer $pid
 */
class FeedbackModel extends \common\components\BaseArModel
{
    /**
     * @var integer 验证码
     */
    public $captcha;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%node_feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['status', 'create_time','pid'], 'integer'],
            [['content'], 'string', 'max' => 500],
            ['captcha', 'captcha','on'=>'form'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
            'status' => '状态',
            'create_time' => '反馈时间',
            'captcha' =>'验证码',
            'pid'=>'父级',
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

    /**
     * 查询回复
     * @return \yii\db\ActiveQuery
     */
    public function getReply(){
        return $this->hasMany(self::className(),['pid'=>'id']);
    }
}
