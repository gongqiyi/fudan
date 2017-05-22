<?php
/**
 * @copyright Copyright (c) 2016 上海稻壳网络科技有限公司
 * @link http://www.dookay.com/
 * @create Created on 2016/12/13
 */

namespace manage\modules\prototype\controllers;
use common\components\manage\ManageController;
use common\entity\models\PrototypeFragmentModel;
use common\entity\models\SystemLogModel;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;


/**
 * 碎片管理
 *
 * @author xiaopig <xiaopig123456@qq.com>
 * @since 1.0
 */
class FragmentController extends ManageController
{
    /**
     *  碎片管理
     */
    public function actionIndex(){
        $model = $this->findModel();
        return $this->render('index', [
            'dataList' => $model::find()->where(['site_id'=>$this->siteInfo->id])->all(),
        ]);
    }

    /**
     * 碎片设置
     */
    public function actionEdit(){
        $model = $this->findModel();
        $dataList = $model::find()->where(['site_id'=>$this->siteInfo->id])->all();

        if (Yii::$app->request->isPost) {
            if(Model::loadMultiple($dataList, Yii::$app->request->post()) && Model::validateMultiple($dataList)){
                foreach ($dataList as $item) {
                    $item->save(false);
                }

                $this->deleteCache();

                SystemLogModel::create('update','更新了内容碎片');

                $this->success(['操作成功','jumpLink'=>'javascript:;']);
            }else{
                $this->error(['操作失败','message'=>$model->getErrorString()]);
            }
        }

        return $this->render($this->action->id, [
            'dataList'=>$dataList
        ]);
    }

    /**
     * 添加碎片
     * @return mixed|void
     */
    public function actionCreate(){
        $model = $this->findModel();
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                $model->site_id = $this->siteInfo->id;
                if($model->setting && strpos($model->setting,'{') === 0){
                    $model->setting = serialize(json_decode(trim($model->setting),true));
                }else{
                    $model->setting = '';
                }
                if($model->save()){
                    $this->deleteCache();

                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrorString()]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 修改碎片
     * @param int $id
     * @return mixed|void
     */
    public function actionUpdate($id){
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                $model->site_id = $this->siteInfo->id;
                if($model->setting && strpos($model->setting,'{') === 0){
                    $model->setting = serialize(json_decode(trim($model->setting),true));
                }else{
                    $model->setting = null;
                }
                if($model->save()){
                    $this->deleteCache();
                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrorString()]);
        }

        if($model->setting){
            $model->setting = json_encode(unserialize($model->setting),JSON_UNESCAPED_UNICODE);
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 删除碎片
     * @param int|string $id
     * @return mixed|void
     */
    public function actionDelete($id){
        $model = $this->findModel($id);

        if($model->delete()){
            $this->deleteCache();
            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 查找模型
     * @param null $id
     * @return PrototypeFragmentModel|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new PrototypeFragmentModel():PrototypeFragmentModel::findOne(['id'=>$id,'site_id'=>$this->siteInfo->id]);
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
        $cacheName = 'fragment';

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