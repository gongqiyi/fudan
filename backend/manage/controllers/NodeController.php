<?php

namespace manage\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\SystemNodeModel;
use common\entity\models\SystemRoleNodeRelationModel;
use common\entity\searches\SystemNodeSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * NodeController implements the CRUD actions for SystemNodeModel model.
 */
class NodeController extends ManageController implements CurdInterface
{
    /**
     * Lists all SystemNodeModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemNodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->asArray();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new SystemNodeModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->findModel();
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->success([Yii::t('common','Operation successful')]);
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrors()]);
        }

        $model->pid = Yii::$app->request->get('pid',1);
        $model->level = Yii::$app->request->get('level',2);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SystemNodeModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->success([Yii::t('common','Operation successful')]);
            }
            $this->error([Yii::t('common','Operation failed')]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SystemNodeModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel();

        $ids = array_merge([$id],$model->find()->where(['pid'=>$id])->asArray()->all());
        if($model->deleteAll(['id'=>$ids])){

            // 删除对应角色权限
            SystemRoleNodeRelationModel::deleteAll(['node_id'=>$ids]);

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * Finds the SystemNodeModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemNodeModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new SystemNodeModel():SystemNodeModel::findOne($id);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    /**
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
     * @param int|string $id
     * @return mixed|void
     */
    public function actionSort($id){}
}
