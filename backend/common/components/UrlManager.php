<?php
// +----------------------------------------------------------------------
// | forgetwork
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/5/23.
// +----------------------------------------------------------------------

/**
 * 路由规则
 */

namespace common\components;


use common\entity\models\PrototypeCategoryModel;
use common\entity\models\SiteModel;
use common\entity\models\SystemConfigModel;
use common\helpers\UrlHelper;
use Yii;
use yii\helpers\ArrayHelper;

class UrlManager extends \yii\web\UrlManager
{
    /**
     * 初始化
     */
    public function init()
    {
        $config = SystemConfigModel::findConfig();
        // 开启伪静态
        $this->enablePrettyUrl = true;
        $this->showScriptName = false;
        $this->suffix = empty($config['site']['urlSuffix'])?'.html':$config['site']['urlSuffix'];

        // node核心路由
        $this->rules = ArrayHelper::merge($this->rules,[
            'index'=>'site/index',
            'category_<category_id>'=>'node/index',
            'category_<category_id>/<id>'=>'node/detail',
            'download_<category_id>'=>'node/download',
            'form/<model_id:\d+>'=>'form/index',
            'site/captcha'=>'site/captcha',
        ]);

        $rules = [];
        foreach (SiteModel::findSite() as $site){
            //if($site['is_default']) continue;
            foreach ($this->rules as $i=>$item){
                $rules['<s:'.$site['slug'].'>/'.$i] = $item;
            }

            // 栏目路由
            foreach(PrototypeCategoryModel::findCategory($site['id']) as $item){
                if(empty($item['slug']) || $item['slug_rules'] == 'site/index') continue;

                $slugs = [];
                foreach(UrlHelper::convertSlugs($item['slug']) as $k=>$v){
                    $slugs[] = '<'.$k.':'.$v.'>';
                }
                switch($item['type']){
                    case 1:
                        $route = 'node/index';
                        break;
                    case 2:
                        $slugRules = UrlHelper::convertSlugRules($item['slug_rules']);
                        $route = $slugRules['route'];
                        if(!empty($item['slug_rules_detail'])){
                            $slugRulesDetail = UrlHelper::convertSlugRules($item['slug_rules_detail']);
                            $this->rules[implode('/',$slugs).'/<id:\d+>'] = $slugRulesDetail['route'];
                            $rules['<s:'.$site['slug'].'>/'.implode('/',$slugs).'/<id:\d+>'] = $slugRulesDetail['route'];
                        }
                        break;
                    default:
                        $route = 'node/index';
                        $this->rules[implode('/',$slugs).'/<id:\d+>'] = 'node/detail';
                        $rules['<s:'.$site['slug'].'>/'.implode('/',$slugs).'/<id:\d+>'] = 'node/detail';
                        break;
                }

                $this->rules[implode('/',$slugs)] = $route;
                $rules['<s:'.$site['slug'].'>/'.implode('/',$slugs)] = $route;
            }
        }

        krsort($rules);
        krsort($this->rules);
        $this->rules = ArrayHelper::merge($rules,$this->rules);

        return parent::init();
    }
}