<?php

namespace common\entity\nodes;

use Yii;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "{{%node_news}}".
 *
 * @property string $id
 * @property integer $model_id
 * @property integer $category_id
 * @property string $title
 * @property string $thumb
 * @property string $thumb_sm
 * @property string $atlas
 * @property string $attachment
 * @property string $description
 * @property string $content
 * @property string $sort
 * @property integer $status
 * @property string $template_content
 * @property integer $is_push
 * @property integer $is_comment
 * @property string $views
 * @property string $create_time
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property integer $site_id
 *
 */
class NewsModel extends \common\components\BaseNodeModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%node_news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'category_id', 'title','site_id'], 'required'],
            [['model_id', 'category_id', 'sort', 'status', 'is_push', 'is_comment', 'views', 'create_time','site_id','create_user_id','update_user_id'], 'integer'],
            [['content','atlas'], 'string'],
            [['title', 'seo_title', 'seo_keywords'], 'string', 'max' => 100],
            [['thumb','attachment','thumb_sm'], 'string', 'max' => 1000],
            [['description'], 'string', 'max' => 255],
            [['template_content'], 'string', 'max' => 50],
            [['seo_description'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => 'Model ID',
            'category_id' => 'Category ID',
            'site_id'=>'Site Id',
            'title' => '标题',
            'thumb' => '缩略图',
            'thumb_sm' => '缩略图标',
            'atlas'=>'图集',
            'description' => '描述',
            'content' => '内容',
            'attachment'=>'附件',
            'sort' => 'Sort',
            'status' => 'Status',
            'template_content' => 'Template Content',
            'is_push' => 'Is Push',
            'is_comment' => 'Is Comment',
            'views' => 'Views',
            'create_time' => 'Create Time',
            'seo_title' => 'Seo Title',
            'seo_keywords' => 'Seo Keywords',
            'seo_description' => 'Seo Description',
            'create_user_id'=>'创建人',
            'update_user_id'=>'最后更新人',
        ];
    }

    /**
     * 自动插入描述
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if(empty($this->description)) $this->description = StringHelper::truncate(strip_tags($this->content),150);

        return parent::beforeSave($insert);
    }
}
