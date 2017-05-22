<?php
// +----------------------------------------------------------------------
// | forgetwork
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/5/25.
// +----------------------------------------------------------------------

/**
 * 评论
 */

namespace home\controllers;

use common\entity\models\PrototypeModelModel;
use Yii;
use yii\web\NotFoundHttpException;

class FormController extends \common\components\home\HomeController
{

    /**
     * node表单模型表单提交
     */
    public function actionIndex(){
        $modelInfo = PrototypeModelModel::findOne(Yii::$app->getRequest()->get('model_id'));
        if($modelInfo && Yii::$app->getRequest()->getIsPost()){
            $model = $this->findModel($modelInfo->name);
            if(array_key_exists('form',$model->scenarios())) $model->setScenario('form');
            if($modelInfo->is_login && Yii::$app->getUser()->getIsGuest()){
                $model->addError('user_id',Yii::t('common','You are not logged in.'));
            }else{
                if($model->load(Yii::$app->getRequest()->post())){
                    if($modelInfo->is_login && isset($model->user_id)) $model->user_id = Yii::$app->getUser()->getId();
                    if($model->save()){
                        $this->success([Yii::t('common','Operation successful'),'jumpLink'=>Yii::$app->getRequest()->post('jumpLink')?:"javascript:void(history.go(-1));"]);
                    }
                }
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrorString()]);
        }
        throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
    }
}