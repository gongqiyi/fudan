<?php

namespace manage\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\SystemRoleModel;
use common\entity\models\SystemRoleUserRelationModel;
use common\entity\models\SystemUserModel;
use common\entity\searches\SystemUserSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for SystemUserModel model.
 */
class UserController extends ManageController implements CurdInterface
{
    /**
     * Lists all SystemUserModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $roleId = Yii::$app->request->get('SystemUserSearch')['roleId'];

        $searchModel = new SystemUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with['roles'];

        $searchModel->roleId = $roleId;

        // 按照角色筛选
        if($roleId){
            $userIds = [];
            foreach(SystemRoleUserRelationModel::find()->where(['role_id'=>$roleId])->asArray()->all() as $item){
                $userIds[] = $item['user_id'];
            }
            $dataProvider->query->where(['in','id',$userIds]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'roleList' => $this->getRoles()
        ]);
    }

    /**
     * Creates a new SystemUserModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->findModel();
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                $model->setUserRoles($model->primaryKey,Yii::$app->request->post('userRoles'));

                $this->success([Yii::t('common','Operation successful')]);
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrors()]);
        }

        return $this->render('create', [
            'model' => $model,
            'roleList' => $this->getRoles(),
            'userRoles' => []
        ]);
    }

    /**
     * Updates an existing SystemUserModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                $model->setUserRoles($model->primaryKey,Yii::$app->request->post('userRoles'));

                $this->success([Yii::t('common','Operation successful')]);
            }
            $this->error([Yii::t('common','Operation failed')]);
        }

        return $this->render('update', [
            'model' => $model,
            'roleList' => $this->getRoles(),
            'userRoles'=> $model->getUserRoles($id)
        ]);
    }

    /**
     * Deletes an existing SystemUserModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel();
        $id = explode(',',$id);

        // 排除删除超级管理员
        $usernames = $model->find()->where(['id'=>$id])->select(['id','username'])->indexBy('username')->asArray()->all();
        $ids = [];
        foreach($usernames as $item){
            if(!array_key_exists(Yii::$app->params['SUPER_ADMIN_NAME'],$usernames)){
                $ids[] = $item['id'];
            }
        }

        if($model->deleteAll(['id'=>$ids])){

            $model->delUserRoles($ids);

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     *
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
     * Finds the SystemUserModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemUserModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new SystemUserModel():SystemUserModel::findOne($id);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    /**
     * 获取角色列表
     * @return array
     */
    protected function getRoles(){
        return SystemRoleModel::find()->all();
    }

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function actionSort($id){}
}
