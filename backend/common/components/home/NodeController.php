<?php
// +----------------------------------------------------------------------
// | dookay
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/5/25.
// +----------------------------------------------------------------------

/**
 * 前台node控制器基类
 */

namespace common\components\home;

use common\entity\models\PrototypePageModel;
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use Yii;
use yii\web\NotFoundHttpException;


class NodeController extends HomeController
{

    /**
     * @var object 当前栏目信息
     */
    public $categoryInfo;

    /**
     * @var array 当前栏目子栏目
     */
    public $subCategoryList = [];

    /**
     * @var array 当前栏目同类型的子栏目id
     */
    public $sameSubCategoryIds = [];

    /**
     * @var array 当前栏目父栏目
     */
    public $parentCategoryList = [];

    /**
     * @var array 系统操作
     */
    private $systemAction = [
        'site/index',
        'site/error',
        'site/captcha',
    ];

    /**
     * 检测是否测试网站并给予提示
     */
    public function init()
    {
        parent::init();

        if(YII_ENV === 'dev' && !Yii::$app->getSession()->has('DEVTIP')){
            Yii::$app->getSession()->set('DEVTIP',true);
            echo $this->renderPartial('@common/assets/dev_tip.php');
            die;
        }
    }

    /**
     * 设置页面栏目信息
     * @param \yii\base\Action $action
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $beforeAction =  parent::beforeAction($action);

        // 根据栏目id查找栏目
        $categoryId = Yii::$app->request->get('category_id');
        if($categoryId && array_key_exists($categoryId,$this->categoryList)){
            $this->categoryInfo = $this->categoryList[$categoryId];
        }
        // 没有栏目id查找栏目
        elseif(in_array($action->getUniqueId(),$this->systemAction)){
            foreach($this->categoryList as $item){
                // 记录首页栏目待用
                if($item['slug_rules'] == 'site/index') $indexCategory = $item;

                if($item['slug_rules'] == $action->getUniqueId()){
                    $this->categoryInfo = $item;
                    break;
                }
            }
            if(empty($this->categoryInfo)) $this->categoryInfo = $indexCategory;
        }
        else{
            // slug处理
            $slugs = [];
            foreach(Yii::$app->getRequest()->get() as $i=>$item){
                $temp = explode('_',$i);
                if($temp[0] === 'slug') $slugs[$temp[1]] = $item;
            }
            ksort($slugs);
            $slug = implode('/',$slugs);

            // 查找栏目
            foreach($this->categoryList as $item){
                if($item['type'] == 2){
                    $slugRules = UrlHelper::convertSlugRules($item['slug_rules']);
                    $slugRulesDetail = UrlHelper::convertSlugRules($item['slug_rules_detail']);

                    if(($slugRules['route'] == $action->getUniqueId() && $item['slug'] == $slug) || ($slugRulesDetail['route'] == $action->getUniqueId() && $item['slug'] == $slug)){
                        $this->categoryInfo = $item;
                        foreach($slugRules['params'] as $k=>$v){
                            $_GET[$k] = $v;
                        }
                        break;
                    }
                }
                elseif($item['slug'] == $slug){
                    $this->categoryInfo = $item;
                    break;
                }
            }
        }

        if(empty($this->categoryInfo)) throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));

        $this->categoryInfo = ArrayHelper::convertToObject($this->categoryInfo);

        // 当前页子栏目列表
        $this->subCategoryList = ArrayHelper::getChildes($this->categoryList,$this->categoryInfo->id);
        array_unshift($this->subCategoryList,$this->categoryList[$this->categoryInfo->id]);

        // 当前栏目同类型子栏目id
        $this->sameSubCategoryIds = ArrayHelper::getColumn($this->findSameCategory($this->subCategoryList,$this->categoryInfo),'id');

        // 当前页父栏目列表
        $this->parentCategoryList = ArrayHelper::getParents($this->categoryList,$this->categoryInfo->id);

        return $beforeAction;
    }

    /**
     * 获取node 列表类容视图
     * @param array $params
     * @return string
     */
    public function findNodeDetailView($params=[]){
        $params = ArrayHelper::merge([
            "default" => 'detail', // 默认视图
            "detail" => null, // 内容
        ],$params);

        if(empty($params['detail']->template_content)){
            $view = $this->findNodeViewPropagation(1,$params['default']);
        }else{
            $view = $params['detail']->template_content;
        }

        return '/'.($this->categoryInfo->type == 2?Yii::$app->controller->id:$this->categoryInfo->model->name).'/'.$view;
    }

    /**
     * 获取node List视图
     * @param string|null $defaultView
     * @return string
     */
    public function findNodeListView($defaultView = 'index'){

        switch($this->categoryInfo->type){
            case 1:
                $view = '/'.'page/'.$this->findNodeViewPropagation(0,$defaultView);
                break;
            case 2:
                $view = $this->findNodeViewPropagation(0,$defaultView);
                break;
            default:
                $view = '/'.$this->categoryInfo->model->name.'/'.$this->findNodeViewPropagation(0,$defaultView);
                break;
        }
        return $view;
    }

    /**
     * 以向上冒泡的方式获取视图文件
     * @param $type 0:获取template字段，1：获取template_content字段
     * @param string $default 默认视图
     * @return array|string
     */
    private function findNodeViewPropagation($type = 0,$default = null){
        $default = empty($default)?'index':$default;
        $viewName = '';
        foreach(array_reverse($this->parentCategoryList,false) as $item){
            if($type === 0 && !empty($item['template'])){
                $viewName = $item['template'];
                break;
            }elseif($type === 1 && !empty($item['template_content'])){
                $viewName = $item['template_content'];
            }
        }
        return empty($viewName)?$default:$viewName;
    }

    /**
     * 列表页
     */
    protected function nodeList(){
        $searchModel = ($this->categoryInfo->model->type == 2)?$this->findSearchModel($this->categoryInfo->model->name,false):$this->findSearchModel($this->categoryInfo->model->name);
        $tableName = $searchModel::tableName();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere([$tableName.'.status'=>1,$tableName.'.site_id'=>$this->siteInfo->id])
            ->andFilterWhere(['in','category_id',$this->sameSubCategoryIds]);

        $dataProvider->sort = [
            'defaultOrder' => [
                'sort'=> SORT_DESC,
            ]
        ];

        $dataProvider->pagination = [
            'pageSize'=>array_key_exists('page_size',Yii::$app->params)?Yii::$app->params['page_size']:10
        ];

        return $this->render($this->findNodeListView(),[
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }

    /**
     * 单页
     */
    protected function nodePage(){
        $model = PrototypePageModel::findOne($this->categoryInfo->id);
        return $this->render($this->findNodeListView(),[
            'dataDetail'=>$model
        ]);
    }

    /**
     * 内容详情页
     */
    protected function nodeDetail(){
        // 是否需要登录
        $this->nodeIsRequiredLogin();

        $model = ($this->categoryInfo->model->type == 2)?$this->findModel($this->categoryInfo->model->name,null,false):$this->findModel($this->categoryInfo->model->name);

        // 内容
        $assign['dataDetail'] = $data = $model->find()->where(['id'=>Yii::$app->request->get('id'),'status'=>1,'site_id'=>$this->siteInfo->id])->one();

        if(!$data) throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));

        // 更新浏览量
        if(isset($data->views)){
            $data->updateCounters(['views'=>1]);
        }

        // 前后翻页按钮
        $assign['prevLink'] = $this->findPageQuery($model,$data,'>',true);
        $assign['nextLink'] = $this->findPageQuery($model,$data,'<');

        return $this->render($this->findNodeDetailView(['detail'=>$data]),$assign);
    }

    /**
     * 判断是否需要登录
     * @return $this
     */
    protected function nodeIsRequiredLogin(){
        if(!empty($this->categoryInfo->model) && $this->categoryInfo->model->is_login && Yii::$app->getUser()->getIsGuest()){
            $url = Yii::$app->getUser()->loginUrl;
            if ($url !== null) {
                $loginUrl = (array) $url;
                if ($loginUrl[0] !== Yii::$app->requestedRoute) {
                    return Yii::$app->getResponse()->redirect($url);
                }
            }
        }
    }

    /**
     * 获取内容上一页和下一页
     * @param object $model 模型
     * @param object $data node内容
     * @param string $sign 符号“<” 或 “>”
     * @param bool $reverse 反转
     * @return mixed
     */
    protected function findPageQuery($model,$data,$sign,$reverse = false){
        // 排序
        $requestSort = Yii::$app->request->get('order');
        $sort = [];
        if($requestSort){
            foreach(array_keys($requestSort) as $item){
                $sort[$item] = ($requestSort[$item] == 'desc')?($reverse?SORT_ASC:SORT_DESC):($reverse?SORT_DESC:SORT_ASC);
            }
        }else{
            $sort = ['sort'=>($reverse?SORT_ASC:SORT_DESC)];
        }

        return $model->find()->where(['status'=>1,'site_id'=>$this->siteInfo->id,'category_id'=>$data->category_id])
            //->andWhere([$sign,'id',$data->id])
            ->andWhere([$sign,'sort',$data->sort])
            ->orderBy($sort)->one();
    }

    /**
     * 重置视图渲染
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        // 是否启用布局
        if(empty($this->categoryInfo->layouts)){
            return $this->renderPartial($view, $params);
        }else{
            $this->layout = $this->categoryInfo->layouts;
            return parent::render($view, $params);
        }
    }

}