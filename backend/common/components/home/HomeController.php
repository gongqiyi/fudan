<?php
// +----------------------------------------------------------------------
// | forgetwork
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/11.
// +----------------------------------------------------------------------

/**
 * 前台控制器基类
 */

namespace common\components\home;

use common\components\BaseController;
use common\entity\models\PrototypeCategoryModel;
use common\entity\models\PrototypeFragmentModel;
use common\entity\models\SiteModel;
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use Yii;
use yii\web\NotFoundHttpException;

class HomeController extends BaseController
{
    /**
     * @var array 站点列表
     */
    public $siteList;

    /**
     * @var array 栏目列表
     */
    public $allCategoryList;

    public $categoryList;

    /**
     * @var bool 是否移动设备
     */
    public $isMobile;

    /**
     * @var object 碎片
     */
    public $fragment;

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();

        $this->config = ArrayHelper::convertToObject($this->config);
        $this->siteList = SiteModel::findSite();

        $s = Yii::$app->getRequest()->get('s');

        foreach ($this->siteList as $item){
            if((!$s && $item['is_default']) || ($s && $s == $item['slug'])){
                $this->siteInfo = ArrayHelper::convertToObject($item);
                break;
            }
        }
        if(!$this->siteInfo) throw new NotFoundHttpException(Yii::t('common','Site does not exist.'));

        //设置站点主题语言
        Yii::$app->language = $this->siteInfo->language;

        $this->isMobile = Yii::$app->mobileDetect->isMobile();

        $currentTheme = ($this->isMobile && $this->siteInfo->enable_mobile)?$this->siteInfo->theme.'-mobile':$this->siteInfo->theme;
        Yii::$app->getView()->theme->setBasePath('@app/themes/'.$currentTheme);
        Yii::$app->getView()->theme->setBaseUrl('@web/themes/'.$currentTheme);
        Yii::$app->getView()->theme->pathMap = [
            '@app/views' => '@app/themes/'.$currentTheme,
            '@app/modules' => '@app/themes/'.$currentTheme,
        ];

        Yii::setAlias('theme','@web/themes/'.$currentTheme);

        // 栏目列表
        $this->allCategoryList = PrototypeCategoryModel::findCategory();
        foreach ($this->allCategoryList as $i=>$item){
            if($item['site_id'] == $this->siteInfo->id) $this->categoryList[$i] = $item;
        }

        // 获取碎片
        $this->fragment = ArrayHelper::convertToObject(PrototypeFragmentModel::findFragment($this->siteInfo->id));
    }

    /**
     * 获取相同类型栏目
     * @param array $categoryList
     * @param array|object $category
     * @return array|object
     */
    public function findSameCategory($categoryList = [],$category){
        $sames = [];
        if(is_object($category)) $category = ArrayHelper::toArray($category);
        foreach($categoryList as $item){
            if(($category['type'] == 0 && $item['model']['name'] ==  $category['model']['name']) || ($category['type'] > 0 && $item['type'] == $category['type'])) $sames[] = $item;
        }
        return empty($sames)?$category:$sames;
    }

    /**
     * 实例化一个node模型
     * @param string $modelName 模型名称
     * @param null|integer $id 数据id
     * @param bool $isNode 是否为node类型
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function findModel($modelName,$id = null,$isNode = true){
        $modelName = '\\common\\entity\\'.($isNode?'nodes':'models').'\\'.ucwords($modelName).'Model';
        $model = empty($id)?new $modelName():$modelName::findOne($id);
        if($model !== null){
            if(array_key_exists('form',$model->scenarios())) $model->setScenario('form');
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    public function findSearchModel($modelName,$isNode = true){
        $modelName = '\\common\\entity\\'.($isNode?'nodes':'searches').'\\'.ucwords($modelName).'Search';
        return new $modelName();
    }

    /**
     * 生成栏目url
     * @param $item
     * @param array $params
     * @return string
     */
    public function generateCategoryUrl($item,$params = []){
        return UrlHelper::categoryPage($item,$this->siteList,ArrayHelper::merge($params,['categoryList'=>$this->allCategoryList,'static'=>true]));
    }

    /**
     * 生成内容详情url
     * @param $item
     * @param array $params
     * @return string
     */
    public function generateDetailUrl($item,$params = []){
        return UrlHelper::detailPage($item,$this->siteList,$this->allCategoryList,ArrayHelper::merge($params,['static'=>true]));
    }

    /**
     * 生成node表单模型url
     * @param $modelId
     * @param array $params
     * @return string
     */
    public function generateFormUrl($modelId,$params = []){
        return UrlHelper::formRequest($modelId,ArrayHelper::merge($params,['static'=>true]));
    }

    /**
     * 生成附件下载url
     * @param $item
     * @param array $params
     * @return string
     */
    public function generateDownloadUrl($item,$params = []){
        return UrlHelper::download($item,ArrayHelper::merge($params,['static'=>true]));
    }
}