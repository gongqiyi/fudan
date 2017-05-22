<?php

namespace manage\modules\slide\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\PrototypeCategoryModel;
use common\entity\models\SlideCategoryModel;
use common\entity\models\SlideModel;
use common\entity\models\SystemLogModel;
use common\entity\searches\SlideSearch;
use common\helpers\ArrayHelper;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * SlideController implements the CRUD actions for SlideModel model.
 */

class SlideController extends ManageController implements CurdInterface
{
    /**
     * Lists all SlideModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SlideSearch();
        $searchModel->category_id = Yii::$app->request->get('category_id');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categoryInfo' => $this->findCategoryInfo($searchModel->category_id)
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
            if($model->load(Yii::$app->request->post())){
                if($model->related_data_model == 0){
                    $model->scenario = 'linkType';
                }
                $model->site_id = $this->siteInfo->id;
                if ($model->save()) {
                    $model->sort = $model->primaryKey;
                    $model->save();

                    SystemLogModel::create('create','在广告“'.$this->findCategoryInfo($model->category_id)->title.'”新增了内容“'.$model->title.'”');

                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrors()]);
        }

        $model->category_id = Yii::$app->request->get('category_id');

        return $this->render('create', [
            'model' => $model,
            'categoryInfo' => $this->findCategoryInfo($model->category_id),
            'modelList'=>$this->findModelList()
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
            if($model->load(Yii::$app->request->post())){
                if($model->related_data_model == 0) $model->scenario = 'linkType';
                $model->site_id = $this->siteInfo->id;
                if ($model->save()) {

                    SystemLogModel::create('update','在广告“'.$this->findCategoryInfo($model->category_id)->title.'”修改了内容“'.$model->title.'”');

                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed')]);
        }

        return $this->render('update', [
            'model' => $model,
            'categoryInfo' => $this->findCategoryInfo($model->category_id),
            'modelList'=>$this->findModelList()
        ]);
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

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

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
                $this->success([Yii::t('common','Operation successful')]);
            }
            $this->error([Yii::t('common','Operation failed')]);
        }

        // 单排序
        if($id === null) $this->error(['操作失败','message'=>'缺少参数id']);
        $currData = $model->find()->where(['id'=>$id])->select(['id','sort'])->asArray()->one();

        $sign = $mode?'>':'<';
        $sort = $mode?['sort'=>SORT_ASC]:['sort'=>SORT_DESC];
        $previewData = $model->find()->where(['category_id'=>Yii::$app->request->get('category_id')])
            ->andWhere([$sign,'sort',$currData['sort']])
            ->orderBy($sort)->select(['id','sort'])->asArray()->one();

        if($previewData){
            $db = Yii::$app->db;
            $sql = $db->createCommand()->update($model->tableName(),['sort'=>$currData['sort']],['id'=>$previewData['id']])->rawSql.';';
            $sql .= $db->createCommand()->update($model->tableName(),['sort'=>$previewData['sort']],['id'=>$currData['id']])->rawSql.';';

            if($db->createCommand($sql)->execute()){
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
        $ids = explode(',',$id);

        $delData = $model->find()->where(['id'=>$ids])->with(['categoryInfo'])->select(['id','category_id','title'])->asArray()->all();

        if($model->deleteAll(['id'=>$ids])){


            foreach ($delData as $item){
                SystemLogModel::create('delete','在广告“'.$item['categoryInfo']['title'].'”下删除内容“'.$item['title'].'”');
            }

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
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
        $model = empty($id)? new SlideModel():SlideModel::findOne($id);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    /**
     * 获取node栏目列表
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function findModelList(){
        $category = Yii::$app->cache->get('category'.$this->siteInfo->id);
        if($category == null){
            $category = PrototypeCategoryModel::find()->where(['site_id'=>$this->siteInfo->id])->orderBy(['sort'=>SORT_ASC,'id'=>SORT_ASC])->asArray()->all();
            Yii::$app->cache->set('category'.$this->siteInfo->id,$category);
        }
        $category =  ArrayHelper::linear($category,' ├ ');

        foreach($category as $i=>$item){
            $category[$i]['title'] = $item['str'].$item['title'];
        }

        return $category;
    }

    /**
     * 获取幻灯片栏目信息
     * @param $id
     * @return SlideCategoryModel
     */
    protected function findCategoryInfo($id){
        return SlideCategoryModel::findOne($id);
    }

}