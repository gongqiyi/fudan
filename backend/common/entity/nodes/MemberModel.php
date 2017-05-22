<?php

namespace common\entity\nodes;

use common\entity\models\PrototypeCategoryModel;
use common\entity\models\SiteModel;
use Yii;

/**
 * This is the model class for table "{{%node_member}}".
 *
 * @property string $id
 * @property integer $model_id
 * @property integer $site_id
 * @property integer $category_id
 * @property string $title
 * @property string $position
 * @property string $direction
 * @property string $thumb
 * @property string $atlas
 * @property string $description
 * @property string $content
 * @property string $attachment
 * @property string $sort
 * @property integer $status
 * @property string $template_content
 * @property integer $is_push
 * @property integer $is_comment
 * @property string $views
 * @property string $create_time
 * @property integer $update_time
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 *
 * @property PrototypeCategoryModel $category
 * @property SiteModel $site
 */
class MemberModel extends \common\components\BaseNodeModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%node_member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'site_id', 'category_id', 'title'], 'required'],
            [['model_id', 'site_id', 'category_id', 'sort', 'status', 'is_push', 'is_comment', 'views', 'create_time', 'update_time','create_user_id','update_user_id'], 'integer'],
            [['atlas', 'content'], 'string'],
            [['title', 'position', 'seo_title', 'seo_keywords'], 'string', 'max' => 100],
            [['direction', 'description'], 'string', 'max' => 255],
            [['thumb', 'attachment'], 'string', 'max' => 1000],
            [['template_content'], 'string', 'max' => 50],
            [['seo_description'], 'string', 'max' => 150],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrototypeCategoryModel::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteModel::className(), 'targetAttribute' => ['site_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => '栏目所属模型id',
            'site_id' => 'Site ID',
            'category_id' => 'Category ID',
            'title' => '标题',
            'position' => '职称',
            'direction' => '研究方向',
            'thumb' => '缩略图',
            'atlas' => '图集',
            'description' => '简介',
            'content' => '内容',
            'attachment' => '附件',
            'sort' => '排序',
            'status' => 'Status',
            'template_content' => '内容模板',
            'is_push' => '是否推荐',
            'is_comment' => '是否允许评论',
            'views' => '浏览数',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'seo_title' => 'Seo Title',
            'seo_keywords' => 'Seo Keywords',
            'seo_description' => 'Seo Description',
            'create_user_id'=>'创建人',
            'update_user_id'=>'最后更新人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(PrototypeCategoryModel::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(SiteModel::className(), ['id' => 'site_id']);
    }
}
