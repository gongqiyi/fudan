<?php
// +----------------------------------------------------------------------
// | forgetwork
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/5/24.
// +----------------------------------------------------------------------

/**
 * 核心控制器
 */

namespace home\controllers;

use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use Yii;
use yii\web\NotFoundHttpException;

class NodeController extends \common\components\home\NodeController
{
    /**
     * 栏目页面
     */
    public function actionIndex(){
        if($this->categoryInfo->type == 0){
            return $this->nodeList();
        }
        elseif($this->categoryInfo->type == 1){
            return $this->nodePage();
        }
    }

    /**
     * 内容详情
     */
    public function actionDetail(){
        return $this->nodeDetail();
    }

    /**
     * 附件下载
     * @param $file
     * @param string $name
     * @throws NotFoundHttpException
     * @internal param string $field
     * @internal param null $cid
     */
    public function actionDownload($file,$name=null){
        // 是否需要登录
        $this->nodeIsRequiredLogin();

        //一次返回102400个字节
        $buffer = 102400;

        $file = urldecode($file);


        if(empty($name)) $name = time();

        $pathInfo = pathinfo($name,PATHINFO_EXTENSION);
        if(empty($pathInfo)){
            $ext = explode('?',pathinfo($file,PATHINFO_EXTENSION));
            $name = $name.'.'.$ext[0];
        }

        // 网络文件
        if(stripos($file,'http://',0)===0 || stripos($file,'https://',0) === 0){
            $file = @ fopen($file, "r");
            if (!$file) {
                echo "文件找不到";
            } else {
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=\"".$name ."\"");
                while (!feof($file)) {
                    echo fread($file, $buffer);
                }
                fclose($file);
            }
        }
        // 本地文件
        else{
            $file = Yii::$app->basePath.'/..'.$file;
            if (!file_exists($file)) throw new NotFoundHttpException(Yii::t('common','File does not exist or has been deleted.'));

            $fp = fopen($file, "r");
            $fileSize = filesize($file);

            $fileData = '';
            while (!feof($fp)) {
                $fileData .= fread($fp, $buffer);
            }
            fclose($fp);

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-type:application/octet-stream;");
            header("Accept-Ranges:bytes");
            header("Accept-Length:{$fileSize}");
            header("Content-Disposition:attachment; filename={$name}");
            header("Content-Transfer-Encoding: binary");
            echo $fileData;
        }
    }
}