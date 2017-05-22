<?php

namespace manage\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\SystemNodeModel;
use common\entity\models\SystemRoleModel;
use common\entity\models\SystemRoleNodeRelationModel;
use common\entity\searches\SystemRoleSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * RoleController implements the CRUD actions for SystemRoleModel model.
 */
class RoleController extends ManageController implements CurdInterface
{
    /**
     * Lists all SystemRoleModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new SystemRoleModel model.
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

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SystemRoleModel model.
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
     * Deletes an existing SystemRoleModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel();
        $id = explode(',',$id);

        if($model->deleteAll(['id'=>$id])){
            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 配置角色权限
     * @param $id
     * @return string
     */
    public function actionAccess($id){
        $assign['roleId'] = $id;

        if($postData = Yii::$app->request->post('Access')){
            // 删除旧权限
            SystemRoleNodeRelationModel::deleteAll(['role_id'=>$id]);

            // 设置新权限
            $newData = [];
            foreach(explode(',',$postData['access']) as $item) {
                $temp['role_id'] = $id;

                $tmp = explode('_',$item);
                $temp['node_id'] = $tmp[0];
                $temp['level'] = $tmp[1];
                $newData[] = $temp;
            }
            Yii::$app->db->createCommand()->batchInsert(SystemRoleNodeRelationModel::tableName(), ['role_id','node_id','level'], $newData)->execute();
            $this->success(['设置权限成功']);
        }

        $this->layout = 'base';

        // 已有权限
        $assign['accessList'] = json_encode(SystemRoleNodeRelationModel::find()->where(['role_id'=>$id])->asArray()->all());

        // 节点列表
        $assign['nodeList'] = json_encode(SystemNodeModel::find()->where(['status'=>1])->asArray()->all());

        return $this->render('access',$assign);
    }

    /**
     * Finds the SystemRoleModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemRoleModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new SystemRoleModel():SystemRoleModel::findOne($id);
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
    public function actionStatus($id){}

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function actionSort($id){}
}
