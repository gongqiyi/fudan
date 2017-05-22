<?php
namespace manage\controllers;

use common\components\manage\ManageController;
use common\entity\models\SiteModel;
use common\entity\models\SystemMenuModel;
use common\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

/**
 * 后台管理
 */
class SiteController extends ManageController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => 5,
                'minLength' => 5,
                'backColor'=>0xfafafa,
                'height'=>32,
                'width' => 90,
                'offset'=>2,
            ],
        ];
    }

    /**
     * 后台首页
     * @param null $sid
     * @return string
     */
    public function actionIndex($sid = null)
    {
        $session = Yii::$app->session;

        if(Yii::$app->getRequest()->getIsPost() && $sid){
            $siteInfo = SiteModel::findOne($sid);
            if($siteInfo){
                $session->set('siteInfo',ArrayHelper::toArray($siteInfo));
                $this->success(['操作成功']);
            }else{
                $this->error(['操作失败','message'=>'站点不存在。']);
            }
        }

        $this->layout = 'base';
        $params = Yii::$app->params;

        // 获取权限
        $access_link = [];
        if($params['USER_AUTH_ON'] || !$session->get($params['ADMIN_AUTH_KEY'])){
            foreach($session->get('_ACCESS_LIST_'.$session->get($params['USER_AUTH_KEY']))['_ACCESS_NAME'][strtoupper(Yii::$app->id)]?:[] as $key=>$value){
                foreach($value as $k=>$v){
                    if(empty($v)){
                        $access_link[] = strtolower($key.'/'.$k);
                    }else{
                        foreach($v as $t=>$tk){
                            $access_link[] = strtolower($key.'/'.$k.'/'.$t);
                        }
                    }
                }
            }
        }

        // 生成和权限相匹配导航
        $navList = [];
        foreach(SystemMenuModel::find()->where(['status'=>1,'group'=>0])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])->asArray()->all() as $key=>$value){
            if(empty($access_link) || in_array(strtolower($value['link']),$access_link) || $value['type'] == 1 || $value['type'] == 2){

                switch($value['type']){
                    case 0:
                    case 2:
                        $str_url = [$value['link']];
                        if($value['param'] && str_replace(' ','',$value['param']) != ''){
                            $params_tmp = [];
                            foreach(explode('&',$value['param']) as $p){
                                $tmp = explode('=',$p);
                                $params_tmp[$tmp[0]] = $tmp[1];
                            }
                            $str_url = array_merge($str_url,$params_tmp);
                        }
                        $value['url'] = Url::to($str_url);
                        break;
                    default:
                        $value['url'] = $value['link'];
                        break;
                }
                $navList[] = $value;
            }
        }

        $assign['navList'] = ArrayHelper::tree($navList);

        // 用户信息
        $assign['userInfo'] = $session->get('userInfo');

        // 获取站点列表
        $assign['siteList'] = SiteModel::find()->where(['is_enable'=>1])->all();

        return $this->render('index',$assign);
    }

    /**
     * 欢迎页
     * @return string
     */
    public function actionWelcome()
    {
        // 系统信息
        $server['serverSoft'] = $_SERVER['SERVER_SOFTWARE'];
        $server['serverOs'] = PHP_OS;

        $server['phpVersion'] = PHP_VERSION;
        $server['fileUpload'] = ini_get('file_uploads') ? ini_get('upload_max_filesize') : '禁止上传';

        // 数据库信息
        $dbSize = 0;
        $connection = Yii::$app->db;
        $command = $connection->createCommand('SHOW TABLE STATUS')->queryAll();
        foreach ($command as $table)
            $dbSize += $table['Data_length'] + $table['Index_length'];
        $mysqlVersion = $connection->createCommand("SELECT version() AS version")->queryAll();
        $server['mysqlVersion'] = $mysqlVersion[0]['version'];
        $server['dbSize'] = Yii::$app->formatter->asSize($dbSize);

        return $this->render($this->action->id,['userInfo'=>Yii::$app->session->get('userInfo'),'server'=>$server]);
    }

}
