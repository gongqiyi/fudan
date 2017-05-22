<?php
/**
 * @copyright Copyright (c) 2016 上海稻壳网络科技有限公司
 * @link http://www.dookay.com/
 * @create Created on 2016/12/21
 */

namespace home\controllers;
use common\helpers\UploadHelper;
use home\forms\UploadForm;
use Yii;


/**
 * 自由页
 *
 * @author xiaopig <xiaopig123456@qq.com>
 * @since 1.0
 */
class FreeController extends NodeController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => 'common\components\home\FreeAction',
        ];
    }

    /**
     * 上传图片
     * @return array|mixed
     */
    public function actionUpload(){
        if(Yii::$app->getRequest()->getIsPost()){
            $result = UploadHelper::uploadImage();
            if(!$result['status']){
                $this->error([Yii::t('common','Operation failed'),'message'=>$result['error']]);
            }else{
                return $this->render('image',[
                    'model'=>new UploadForm(),
                    'files'=>json_encode($result['files']),
                ]);
            }
        }

        return $this->render('image',[
            'model'=>new UploadForm(),
        ]);
    }

    public function actionOrganization(){
        return $this->render($this->action->id);
    }
    public function actionTeam(){
        return $this->render($this->action->id);
    }

}