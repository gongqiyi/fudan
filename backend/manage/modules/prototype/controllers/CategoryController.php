<?php

namespace manage\modules\prototype\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\PrototypeCategoryModel;
use common\entity\models\PrototypeModelModel;
use common\entity\models\PrototypePageModel;
use common\entity\models\SystemLogModel;
use common\entity\searches\PrototypeCategorySearch;
use common\helpers\ArrayHelper;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * CategoryController implements the CRUD actions for PrototypeCategoryModel model.
 */
class CategoryController extends ManageController implements CurdInterface
{
    /**
     * @var array 菜单类型列表
     */
    public $categoryTypeList = [
        0 => '数据列表',
        1 => '单网页',
        2 => '自由页',
        3 => '外部链接'
    ];

    /**
     * Lists all PrototypeCategoryModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrototypeCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['site_id'=>$this->siteInfo->id])->with(['model'])->asArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categoryList'=>$this->getCategory(),
            'modelList'=>$this->getModel(),
            'pid'=>Yii::$app->request->get('pid',0)
        ]);
    }

    /**
     * Creates a new PrototypeCategoryModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->findModel();
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                $model->site_id = $this->siteInfo->id;
                if(is_array($model->expand)){
                    $model->expand = json_encode($model->expand);
                }else{
                    $model->expand = null;
                }
                if($model->save()){
                    $model->sort = $model->primaryKey;
                    $model->save();

                    // 生成单页
                    if($model->type == 1){
                        $pageModel = new PrototypePageModel();
                        $pageModel->category_id = $model->primaryKey;
                        $pageModel->title = $model->title;
                        $pageModel->save();
                    }

                    // 删除栏目缓存
                    $this->delCategoryCache();

                    SystemLogModel::create('create','新增栏目“'.$model->title."”");

                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrorString()]);
        }

        $model->type = Yii::$app->request->get('type',0);
        $model->pid = Yii::$app->request->get('pid',0);
        return $this->render('create', [
            'model' => $model,
            'categoryList'=>$this->getCategory(),
            'modelList'=>$this->getModel(),
        ]);
    }

    /**
     * Updates an existing PrototypeCategoryModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())){
                if(is_array($model->expand)){
                    $model->expand = json_encode($model->expand);
                }else{
                    $model->expand = null;
                }
                if($model->save()){
                    // 删除栏目缓存
                    $this->delCategoryCache();

                    SystemLogModel::create('update','编辑了栏目“'.$model->title."”");

                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrorString()]);
        }

        if(!empty($model->expand)){
            $model->expand = json_decode($model->expand);
        }

        return $this->render('update', [
            'model' => $model,
            'categoryList'=>$this->getCategory(),
            'modelList'=>$this->getModel(),
        ]);
    }

    /**
     * Deletes an existing PrototypeCategoryModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $title = $model->title;

        // 获取子菜单
        $categoryList = $this->getCategory();
        $ids[] = $id;
        $pageIds = []; // 单页类型子菜单
        foreach(ArrayHelper::getChildes($categoryList,$id) as $item){
            $ids[] = $item['id'];
            if($item['type'] == 1) $pageIds[] = $item['id'];
        }

        if($model->deleteAll(['id'=>$ids])){
            // 删除单页类型扩展数据
            if(!empty($pageIds)) PrototypePageModel::deleteAll(['category_id'=>$pageIds]);

            // 删除栏目缓存
            $this->delCategoryCache();

            SystemLogModel::create('delete','删除栏目“'.$title."”及其子栏目");

            $this->success([Yii::t('common','Operation successful'),'jumpLink'=>'javascript:history.go(0)']);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 状态设置
     * @param int|string $id
     * @return mixed|void
     */
    public function actionStatus($id){
        $model = $this->findModel();
        $id = explode(',',$id);

        if($model->updateAll(['status'=>Yii::$app->request->get('value',0)],['id'=>$id])){

            // 删除栏目缓存
            $this->delCategoryCache();

            SystemLogModel::create('update','更新了栏目状态');

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 数据排序
     * @return mixed|void
     */
    public function actionSort(){
        $model = $this->findModel();

        // 批量排序
        if(Yii::$app->getRequest()->getIsPost()){
            $postData = json_decode(Yii::$app->getRequest()->post('data'));
            $db = Yii::$app->db;
            $sql = '';
            foreach ($postData as $item){
                $sql .= $db->createCommand()->update($model->tableName(),['sort'=>$item->sort,'pid'=>$item->pid],['id'=>$item->id])->rawSql.';';
            }
            if($sql){
                $db->createCommand($sql)->execute();
                $this->delCategoryCache();

                SystemLogModel::create('update','对栏目进行了排序');

                $this->success([Yii::t('common','Operation successful')]);
            }
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 返回栏目左侧导航菜单
     * @param bool $render
     * @return string
     */
    public function actionExpand_nav($render = true){
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($render){
            $dataList = ArrayHelper::tree($this->getCategory(false));
            return $this->renderPartial('expand_nav',['dataList'=>$dataList]);
        }else{
            return $this->getCategory(false);
        }
    }

    /**
     * Finds the PrototypeCategoryModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrototypeCategoryModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new PrototypeCategoryModel():PrototypeCategoryModel::findOne(['id'=>$id,'site_id'=>$this->siteInfo->id]);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    /**
     * 获取模型列
     * @return array|\common\entity\domains\PrototypeModelDomain[]
     */
    protected function getModel(){
        return PrototypeModelModel::find()->where(['type'=>[0,2]])->all();
    }

    /**
     * 获取菜单列
     * @param bool $titleHandle 是否对标题进行处理
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getCategory($titleHandle = true){
        $category = Yii::$app->cache->get('category'.$this->siteInfo->id);
        if($category == null){
            $category = $this->findModel()->find()->where(['site_id'=>$this->siteInfo->id])->indexBy('id')->with(['model'])->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])->asArray()->all();
            Yii::$app->cache->set('category'.$this->siteInfo->id,$category);
        }
        $category =  ArrayHelper::linear($category,' ├ ');
        if($titleHandle){
            foreach($category as $i=>$item){
                $category[$i]['title'] = $item['str'].$item['title'];
            }
        }
        return $category;
    }

    /**
     * 删除栏目缓存
     */
    protected function delCategoryCache(){
        $cacheName = 'category';

        Yii::$app->cache->delete($cacheName.$this->siteInfo->id);

        $token = Yii::$app->getSecurity()->generatePasswordHash($cacheName.date('Ymdh',time()));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => Yii::$app->getRequest()->getHostInfo()."/api/del-cache.html",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"type\"\r\n\r\n$cacheName\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"token\"\r\n\r\n$token\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: 77c9c75a-c15c-6033-a9fb-fcbe9ad87c86"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    }
}
