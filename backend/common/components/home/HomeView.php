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
 * 主站点视图基类
 */

namespace common\components\home;

use common\components\BaseView;
use common\entity\models\PrototypeModelModel;
use common\helpers\ArrayHelper;

class HomeView extends BaseView
{
    /**
     * 设置页面基本信息
     * @param string $viewFile
     * @param array $params
     * @return bool
     */
    public function beforeRender($viewFile, $params)
    {
        $beforeRender = parent::beforeRender($viewFile, $params);

        $isTitle = empty($this->title);

        // 栏目页seo
        if(isset($this->context->categoryInfo)){
            if($isTitle){
                $this->title = empty($this->context->categoryInfo->seo_title)?$this->context->categoryInfo->title:$this->context->categoryInfo->seo_title;
            }
            if(empty($this->keywords)) $this->keywords = $this->context->categoryInfo->seo_keywords;
            if(empty($this->description)) $this->description = $this->context->categoryInfo->seo_description;
        }

        // 详情页seo
        if(array_key_exists('dataDetail',$params) && $this->context->categoryInfo->type !=1){
            if($isTitle) {
                if (empty($params['dataDetail']->seo_title)) {
                    if (isset($params['dataDetail']->title)) $this->title = $params['dataDetail']->title;
                } else {
                    $this->title = $params['dataDetail']->seo_title;
                }
            }
            if(!empty($params['dataDetail']->seo_keywords)) $this->keywords = $params['dataDetail']->seo_keywords;
            if(!empty($params['dataDetail']->seo_description)) $this->description = $params['dataDetail']->seo_description;
        }

        return $beforeRender;
    }

    /**
     * 实例化一个模型
     * @param $modelId
     * @param null $nodeId
     * @return mixed
     */
    public function findModel($modelId,$nodeId = null){
        $modelInfo = PrototypeModelModel::findOne($modelId);
        return $this->context->findModel($modelInfo->name,$nodeId);
    }

    /**
     * 获取碎片数据
     * @param string|int|array $data string：数据模型名称，int或array：栏目id,
     * @param array|null $sort 排序
     * @param bool $query 是否立即查询
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findFragment($data,$sort = [],$query = true){
        if(is_string($data)){
            $modelName = '\\common\\entity\\models\\'.ucwords($data).'Model';
        }else{
            if (!is_array($data)){
                $data = [$data];
            }
            $categoryInfo = $this->context->categoryList[$data[0]];
            $modelName = '\\common\\entity\\nodes\\'.ucwords($categoryInfo['model']['name']).'Model';
            if(empty($sort)) $sort = ['sort'=>SORT_DESC,'id'=>SORT_DESC];
        }

        $model = new $modelName();
        $result = $model->find();

        if(isset($categoryInfo)){
            foreach ($data as $item){
                $childes[] = $this->context->categoryList[$item];
                $childes = ArrayHelper::merge($childes,ArrayHelper::getChildes($this->context->categoryList,$item));
            }
            $result->andWhere(['category_id'=>ArrayHelper::getColumn($this->context->findSameCategory($childes,$categoryInfo),'id')]);
        }

        if(array_key_exists('status',$model->attributes)) $result->andWhere(['status'=>1]);

        if($sort && is_array($sort)) $result->orderBy($sort);

        if($query) $result->limit(\Yii::$app->params['page_size']);

        return $query?$result->all():$result;
    }

    /**
     * 获取单网页碎片
     * @param int|array $categoryId
     * @return mixed
     */
    public function findFragmentPage($categoryId){
        if(!is_array($categoryId)) $categoryId = [$categoryId];
        $data = $this->findFragment('prototypePage',[],false)->andWhere(['category_id'=>$categoryId]);
        return count($categoryId) > 1?$data->all():$data->one();
    }

    /**
     * 获取幻灯片数据
     * @param int $adCategoryId 幻灯片栏目id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAdList($adCategoryId){
        $dataList = $this->findFragment('slide',['sort'=>SORT_DESC],false)->andWhere(['category_id'=>$adCategoryId])->all();
        foreach($dataList as $i=>$item){
            if($item->related_data_model > 0){
                if($item->related_data_id > 0){
                    $dataList[$i]->link = $this->generateDetailUrl(['id'=>$item->related_data_id,'category_id'=>$item->related_data_model]);
                }else{
                    $dataList[$i]->link = $this->generateCategoryUrl($this->context->categoryList[$item->related_data_model]);
                }
            }
        }
        return $dataList;
    }

    /**
     * 根据栏目id查找对应栏目
     * @param null $categoryId 栏目id
     * @return null|object
     */
    public function findCategoryById($categoryId = null){
        if(empty($categoryId)) return $this->context->categoryInfo;
        return array_key_exists($categoryId,$this->context->categoryList)?ArrayHelper::convertToObject($this->context->categoryList[$categoryId]):null;
    }

    /**
     * 生成栏目url
     * @param $item
     * @param array $params
     * @return string
     */
    public function generateCategoryUrl($item,$params = []){
        return $this->context->generateCategoryUrl($item,$params);
    }

    /**
     * 生产内容详情url
     * @param $item
     * @param array $params
     * @return string
     */
    public function generateDetailUrl($item,$params = []){
        return $this->context->generateDetailUrl($item,$params);
    }

    /**
     * 生产node表单模型url
     * @param $modelId
     * @param array $params
     * @return string
     */
    public function generateFormUrl($modelId,$params = []){
        return $this->context->generateFormUrl($modelId,$params);
    }

    /**
     * 生成附件下载url
     * @param $item
     * @param array $params
     * @return string
     */
    public function generateDownloadUrl($item,$params = []){
        return $this->context->generateDownloadUrl($item,$params);
    }
}