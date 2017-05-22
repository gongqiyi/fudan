<?php

namespace manage\modules\slide\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\SlideCategoryModel;
use common\entity\models\SlideModel;
use common\entity\searches\SlideCategorySearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * CategoryController implements the CRUD actions for SlideModel model.
 */

class CategoryController extends ManageController implements CurdInterface
{
    /**
     * Lists all SlideModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SlideCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['site_id'=>$this->siteInfo->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new SlideModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->findModel();
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                if($model->enable_mobile == 1){
                    $model->thumb_size = null;
                }
                $model->site_id = $this->siteInfo->id;
                if($model->save()){
                    $model->sort = $model->primaryKey;
                    $model->save();

                    $this->delCategoryCache();

                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrors()]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SlideModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                if($model->enable_mobile == 1){
                    $model->thumb_size = null;
                }
                if($model->save()){
                    $this->delCategoryCache();
                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed')]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function actionStatus($id){}

    /**
     * 数据排序
     * @param int|null $id
     * @param int|null $mode 0|1
     * @return mixed|void
     */
    public function actionSort($id = null,$mode = null){
        $model = $this->findModel();

        // 批量排序
        if(Yii::$app->getRequest()->getIsPost()){
            $postData = json_decode(Yii::$app->getRequest()->post('data'));
            $db = Yii::$app->db;
            $sql = '';
            foreach ($postData as $item){
                $sql .= $db->createCommand()->update($model->tableName(),['sort'=>intval($item->sort)],['id'=>$item->id])->rawSql.';';
            }
            if($sql){
                $db->createCommand($sql)->execute();
                $this->delCategoryCache();
                $this->success([Yii::t('common','Operation successful')]);
            }
            $this->error([Yii::t('common','Operation failed')]);
        }

        // 单排序
        if($id === null) $this->error(['操作失败','message'=>'缺少参数id']);
        $currData = $model->find()->where(['id'=>$id])->select(['id','sort'])->asArray()->one();

        $sign = $mode?'<':'>';
        $sort = $mode?['sort'=>SORT_DESC]:['sort'=>SORT_ASC];
        $previewData = $model->find()
            ->where([$sign,'sort',$currData['sort']])
            ->orderBy($sort)->select(['id','sort'])->asArray()->one();

        if($previewData){
            $db = Yii::$app->db;
            $sql = $db->createCommand()->update($model->tableName(),['sort'=>$currData['sort']],['id'=>$previewData['id']])->rawSql.';';
            $sql .= $db->createCommand()->update($model->tableName(),['sort'=>$previewData['sort']],['id'=>$currData['id']])->rawSql.';';

            if($db->createCommand($sql)->execute()){
                $this->delCategoryCache();
                $this->success([Yii::t('common','Operation successful')]);
            }
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * Deletes an existing SlideModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel();
        $id = explode(',',$id);

        if($model->deleteAll(['id'=>$id])){
            $this->delCategoryCache();

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 返回栏目左侧导航菜单
     */
    public function actionExpand_nav(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->renderPartial('expand_nav',['dataList'=>$this->findCategory()]);
    }

    /**
     * Finds the SlideModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SlideModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new SlideCategoryModel():SlideCategoryModel::findOne(['id'=>$id,'site_id'=>$this->siteInfo->id]);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    /**
     * 获取分类
     * @return array
     * @throws NotFoundHttpException
     */
    protected function findCategory(){
        $category = Yii::$app->cache->get('slideCategory'.$this->siteInfo->id);
        if($category == null){
            $category = $this->findModel()->find()->where(['site_id'=>$this->siteInfo->id])->indexBy('id')->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])->asArray()->all();
            Yii::$app->cache->set('slideCategory'.$this->siteInfo->id,$category);
        }
        return $category;
    }

    /**
     * 删除幻灯片分类缓存
     */
    protected function delCategoryCache(){
        Yii::$app->cache->delete('slideCategory'.$this->siteInfo->id);
    }
}