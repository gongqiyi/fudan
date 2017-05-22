<?php
/**
 * @copyright Copyright (c) 2016 上海稻壳网络科技有限公司
 * @link http://www.dookay.com/
 * @create Created on 2016/12/20
 */

namespace manage\controllers;
use common\components\manage\ManageController;
use common\entity\models\SiteModel;
use Yii;
use yii\web\NotFoundHttpException;


/**
 * 站点管理
 *
 * @author xiaopig <xiaopig123456@qq.com>
 * @since 1.0
 */
class SiteManageController extends ManageController
{
    /**
     * 站点列表
     */
    public function actionIndex(){
        $model = $this->findModel();

        return $this->render('index',[
            'dataList'=>$model::find()->all()
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
                $this->deleteCache();
                $this->success([Yii::t('common','Operation successful')]);
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrors()]);
        }

        $model->loadDefaultValues();
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
                $this->deleteCache();
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
        $model = $this->findModel($id);
        if(!$model->is_default && $this->siteInfo->id != $model->id){
            if($model->delete()){
                $this->deleteCache();
                $this->success([Yii::t('common','Operation successful')]);
            }else{
                $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrorString()]);
            }
        }
        $this->error([Yii::t('common','Operation failed'),'message'=>'默认站点不可删除。']);
    }

    /**
     * 设置默认站点
     * @param $id
     */
    public function actionSetDefault($id){
        $model = $this->findModel();
        $model::updateAll(['is_default'=>0]);
        $model::updateAll(['is_default'=>1],['id'=>$id]);
        $this->deleteCache();
        $this->success([Yii::t('common','Operation successful')]);
    }

    /**
     * 状态设置
     * @param int|string $id
     * @return mixed|void
     */
    public function actionStatus($id){
        $model = $this->findModel();
        $id = explode(',',$id);

        if($model->updateAll(['is_enable'=>Yii::$app->request->get('value',0)],['id'=>$id])){
            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * Finds the SystemRoleModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SiteModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new SiteModel():SiteModel::findOne($id);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    /**
     * 删除缓存
     */
    public function deleteCache(){
        $cacheName = 'site';

        Yii::$app->cache->delete($cacheName);

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