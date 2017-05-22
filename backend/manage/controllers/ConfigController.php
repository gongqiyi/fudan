<?php

namespace manage\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\SystemConfigModel;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * ConfigController implements the CRUD actions for SystemConfigModel.
 */
class ConfigController extends ManageController implements CurdInterface
{
    /**
     * @var array 配置类型
     */
    private $configTitle = array(
        'site'=>'站点设置',
        'email'=>'邮件设置',
        'upload'=>'上传设置',
        'custom'=>'自定义配置',
        'qiniu'=>'七牛云设置',
    );

    /**
     * 配置更新
     * @return string
     */
    public function actionIndex()
    {
        $assign['scope'] = Yii::$app->request->get('scope','site');
        $assign['config'] = SystemConfigModel::find()->where(['scope'=>$assign['scope']])->indexBy('id')->all();

        if (Yii::$app->request->isPost) {
            if(Model::loadMultiple($assign['config'], Yii::$app->request->post()) && Model::validateMultiple($assign['config'])){
                foreach ($assign['config'] as $item) {
                    $item->save(false);
                }
                $this->deleteCache();
                $this->success(['操作成功','jumpLink'=>'javascript:;']);
            }else{
                $this->error(['操作失败']);
            }
        }

        $assign['title'] = $this->configTitle[$assign['scope']];

        return $this->render($this->action->id, $assign);
    }

    /**
     *  自定义配置管理
     */
    public function actionCustom(){
        return $this->render('custom', [
            'dataList' => SystemConfigModel::find()->where(['scope'=>'custom'])->all(),
        ]);
    }

    /**
     * 添加自定义配置
     * @return mixed|void
     */
    public function actionCreate(){
        $model = $this->findModel();
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                $model->scope = 'custom';
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
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrors()]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 修改自定义配置
     * @param int $id
     * @return mixed|void
     */
    public function actionUpdate($id){
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                $model->scope = 'custom';
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
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrors()]);
        }

        if($model->setting){
            $model->setting = json_encode(unserialize($model->setting),JSON_UNESCAPED_UNICODE);
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 删除自定义配置
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
     * @return SystemConfigModel|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new SystemConfigModel():SystemConfigModel::findOne($id);
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

    /**
     * 删除缓存
     */
    public function deleteCache(){
        $cacheName = 'config';

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
