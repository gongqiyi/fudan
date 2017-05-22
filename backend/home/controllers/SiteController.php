<?php
namespace home\controllers;

use common\entity\nodes\MemberModel;
use home\forms\LoginForm;
use Yii;

/**
 * Site controller
 */
class SiteController extends NodeController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => 4,
                'minLength' => 4,
                'backColor'=>0xfafafa,
                'height'=>32,
                'width' => 90,
                'offset'=>2,
            ],
            'error'=>'yii\web\ErrorAction',
        ];
    }

    /**
     * 首页
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNav(){
        return $this->renderPartial('nav');
    }

    public function actionDetails(){
        $dataDetail = MemberModel::findOne(Yii::$app->request->get('id'));
        return $this->renderPartial('/member/_detail',[
            'dataDetail' =>$dataDetail
        ]);
    }

    /**
     * 用户登陆
     * @param $jumpLink
     * @return string
     */
    public function actionLogin($jumpLink = 'javascript:history.go(-1);'){
        $model = new LoginForm();
        $request = Yii::$app->request;
        if($request->getIsPost()){
            if($model->load($request->post()) && $model->signIn()){
                $this->success([Yii::t('common','Operation successful'),'jumpLink'=>$jumpLink]);
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrorString()]);
        }

        return $this->render('login',['model'=>$model]);
    }

    /**
     * 退出
     */
    public function actionLogout(){
        Yii::$app->getUser()->logout();
        $this->success([Yii::t('common','Operation successful')]);
    }
}
