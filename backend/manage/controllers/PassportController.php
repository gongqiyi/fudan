<?php
// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/4.
// +----------------------------------------------------------------------

/**
 * 通行证
 */

namespace manage\controllers;


use common\components\manage\ManageController;
use manage\models\LoginForm;
use Yii;
use yii\helpers\Url;

class PassportController extends ManageController
{

    /**
     * @var string 布局
     */
    public $layout = 'passport';

    /**
     * 登陆
     * @return string
     */
    public function actionLogin(){
        // 判断是否已经登录
        if(Yii::$app->session->get(Yii::$app->params['USER_AUTH_KEY'])){
            $assign['userInfo'] = Yii::$app->session->get('userInfo');
        }

        $model = new LoginForm();

        if (Yii::$app->request->isPost) {
            if($model->load(Yii::$app->request->post()) && $model->login()){
                $this->success(['登陆成功','jumpLink'=>Url::to(['site/index'])]);
            }
            $this->error(['登陆失败','message'=>'用户名或密码错误']);
        }

        $assign['model'] = $model;

        return $this->render('login',$assign);
    }

    /**
     * 退出
     */
    public function actionLogout(){
        Yii::$app->session->removeAll();;
        $this->redirect(Url::to(['passport/login']));
    }

}