<?php
/**
 * @copyright Copyright (c) 2016 上海稻壳网络科技有限公司
 * @link http://www.dookay.com/
 * @create Created on 2016/9/19
 */

namespace common\helpers;
use home\forms\UploadForm;
use Yii;
use yii\base\Object;
use yii\web\UploadedFile;


/**
 * UploadHelper
 *
 * @author xiaopig <xiaopig123456@qq.com>
 * @since 1.0
 */
class UploadHelper extends Object
{
    /**
     * 上传图片
     * @param bool $multiple
     * @param bool $isGet 是否get请求
     * @return array
     */
    static public function uploadImage($multiple = false,$isGet = false){
        $model = new UploadForm();
        $model->scenario = 'image';

        $requestData = $isGet?Yii::$app->getRequest()->get('UploadForm'):Yii::$app->getRequest()->post('UploadForm');

        $model->load(['UploadForm'=>$requestData]);
        $model->imageFile = $multiple?UploadedFile::getInstances($model, 'imageFile'):UploadedFile::getInstance($model, 'imageFile');
        if ($files = $model->upload()) {
            return ['status'=>1, 'files'=>$files];
        }

        return ['status'=>0, 'error'=>$model->getErrorString()];
    }

    /**
     * 上传附件
     * @param bool $multiple
     * @param bool $isGet 是否get请求
     * @return array
     */
    static public function uploadAttachment($multiple = false,$isGet = false)
    {
        $model = new UploadForm();

        $model->scenario = 'file';

        $requestData = $isGet?Yii::$app->getRequest()->get('UploadForm'):Yii::$app->getRequest()->post('UploadForm');

        $model->load(['UploadForm'=>$requestData]);
        $model->attachment = $multiple?UploadedFile::getInstances($model, 'attachment'):UploadedFile::getInstance($model, 'attachment');
        if ($files = $model->upload()) {

            return ['status'=>1, 'files'=>$files];
        }

        return ['status'=>0, 'error'=>$model->getErrorString()];
    }
}