<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%system_menu}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $type
 * @property integer $group
 * @property integer $category_id
 * @property string $title
 * @property integer $status
 * @property integer $sort
 * @property string $link
 * @property string $param
 */
class SystemMenuDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'type', 'title'], 'required'],
            [['pid', 'type', 'status','group', 'sort','category_id'], 'integer'],
            [['title'], 'string', 'max' => 70],
            [['link'], 'string', 'max' => 100],
            [['param'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父级菜单',
            'group'=>'菜单组',
            'type' => '菜单类型',
            'title' => '菜单名称',
            'status' => '状态',
            'sort' => '排序',
            'link' => '菜单链接',
            'param' => '链接参数',
            'category_id'=>'绑定栏目'
        ];
    }
}
