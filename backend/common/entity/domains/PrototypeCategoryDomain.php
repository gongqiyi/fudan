<?php

namespace common\entity\domains;

use common\entity\models\SiteModel;
use common\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "{{%prototype_category}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $model_id
 * @property integer $type
 * @property string $title
 * @property string $sub_title
 * @property string $slug_rules
 * @property string $slug_rules_detail
 * @property string $slug
 * @property integer $sort
 * @property integer $status
 * @property string $link
 * @property string $thumb
 * @property string $content
 * @property string $template
 * @property string $template_content
 * @property string $expand
 * @property string $seo_title
 * @property string $seo_keywords
 * @property integer $layouts
 * @property string $seo_description
 * @property integer $site_id
 */
class PrototypeCategoryDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prototype_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug','slug_rules','slug_rules_detail'],'trim'],
            [['pid', 'model_id', 'type', 'sort', 'status','site_id'], 'integer'],
            [['title'], 'required'],
            [['content','expand'], 'string'],
            [['title', 'slug_rules','slug_rules_detail', 'slug', 'link', 'seo_title', 'seo_keywords','sub_title'], 'string', 'max' => 100],
            [['thumb'], 'string', 'max' => 1000],
            [['target'], 'string', 'max' => 20],
            [['template', 'template_content','layouts'], 'string', 'max' => 50],
            [['seo_description'], 'string', 'max' => 255],
            [['slug', 'site_id'], 'unique', 'targetAttribute' => ['slug', 'site_id'],'when'=>function($model){
                return !empty($model->slug);
            }],
            [['slug'], 'match', 'pattern' => '/^[A-Za-z][(\w|\-)*\/]*[A-Za-z0-9]$/'],
            ['slug',function($attribute,$params){
                if (!$this->hasErrors()) {
                    $siteList = ArrayHelper::getColumn(SiteModel::findSite(),'slug');
                    $slug = explode('/',$this->$attribute);
                    if(in_array($slug[0],$siteList)){
                        $this->addError($attribute,$this->getAttributeLabel($attribute).'开头包含系统保留关键字。');
                    }
                }
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '父级栏目',
            'model_id' => '所属模型',
            'type' => '栏目类型',
            'title' => '栏目名称',
            'sub_title'=>'名称扩展',
            'slug' => 'Url美化',
            'slug_rules' => '页面路由',
            'slug_rules_detail'=>'详情页路由',
            'sort' => '排序',
            'status' => '状态',
            'link' => '跳转链接',
            'thumb' => '栏目图片',
            'content' => '栏目描述',
            'template' => '列表模板',
            'template_content' => '内容模板',
            'expand'=>'其他扩展数据',
            'seo_title' => 'SEO标题',
            'seo_keywords' => 'SEO关键字',
            'seo_description' => 'SEO描述',
            'layouts'=>'页面布局',
            'target'=>'打开链接目标',
            'site_id'=>'站点ID',
        ];
    }
}
