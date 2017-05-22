<?php
// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/11.
// +----------------------------------------------------------------------

/**
 * 后台控制器基类
 */

namespace common\components\manage;

use common\components\BaseController;
use common\entity\models\SiteModel;
use common\helpers\ArrayHelper;
use manage\libs\Rbac;
use Yii;

class ManageController extends BaseController
{
    /**
     * @var bool 是否超级管理员
     */
    public $isSuperAdmin = false;

    /**
     * init
     */
    public function init()
    {
        parent::init();

        $session = Yii::$app->getSession();
        if($session->has(Yii::$app->params['ADMIN_AUTH_KEY'])) $this->isSuperAdmin = true;

        if($siteInfo = $session->get('siteInfo')){
            $this->siteInfo = ArrayHelper::convertToObject($siteInfo);
        }else{
            $this->siteInfo = SiteModel::find()->where(['is_default'=>1])->one();
            $session->set('siteInfo',ArrayHelper::toArray($this->siteInfo));
        }
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action){

        /*$desc = ['index'=>'列表','create'=>'创建','update'=>'更新','status'=>'状态','sort'=>'排序','delete'=>'删除'];

        $tmp = explode('/',$action->getUniqueId());
        array_unshift($tmp,Yii::$app->id);
        $model = new SystemNodeModel();

        $data_1 = $data_2 = $data_3 = [];
        foreach($tmp as $i=>$item){
            switch($i){
                case 0:
                    $level_1 = SystemNodeModel::find()->where(['name'=>$item,'pid'=>0])->one();
                    if(empty($level_1)){
                        $level_1_model = clone $model;
                        $level_1_model->pid = 0;
                        $level_1_model->name = $item;
                        $level_1_model->title = $item;
                        $level_1_model->level = $i+1;
                        $level_1_model->save();
                        $data_1 = $level_1_model;
                    }else{
                        $data_1 = $level_1;
                    }
                    break;
                case 1:
                    $level_2 = SystemNodeModel::find()->where(['name'=>$item,'pid'=>$data_1->id])->one();
                    if(empty($level_2)){
                        $level_2_model = clone $model;
                        $level_2_model->pid = $data_1->id;
                        $level_2_model->name = $item;
                        $level_2_model->title = array_key_exists($item,$desc)?$desc[$item]:$item;
                        $level_2_model->level = $i+1;
                        $level_2_model->save();
                        $data_2 = $level_2_model;
                    }else{
                        $data_2 = $level_2;
                    }
                    break;
                case 2:
                    $level_3 = SystemNodeModel::find()->where(['name'=>$item,'pid'=>$data_2->id])->one();
                    if(empty($level_3)){
                        $level_3_model = clone $model;
                        $level_3_model->pid = $data_2->id;
                        $level_3_model->name = $item;
                        $level_3_model->title = array_key_exists($item,$desc)?$desc[$item]:$item;
                        $level_3_model->level = $i+1;
                        $level_3_model->save();
                        $data_3 = $level_3_model;
                    }else{
                        $data_3 = $level_3;
                    }
                    break;
                case 3:
                    $level_4 = SystemNodeModel::find()->where(['name'=>$item,'pid'=>$data_3->id])->one();
                    if(empty($level_4)){
                        $level_4_model = clone $model;
                        $level_4_model->pid = $data_3->id;
                        $level_4_model->name = $item;
                        $level_4_model->title = array_key_exists($item,$desc)?$desc[$item]:$item;
                        $level_4_model->level = $i+1;
                        $level_4_model->save();
                    }
                    break;
            }
        }*/


        // 登陆权限认证
        if(!Rbac::checkLogin(Yii::$app->id,$action->getUniqueId())){
            $this->redirect(['passport/login']);
            return false;
        }

        if(!Rbac::checkAccess(Yii::$app->id,$action->getUniqueId())){
            $this->error(['很遗憾，您没有权限访问']);
        }

        return parent::beforeAction($action);
    }
}