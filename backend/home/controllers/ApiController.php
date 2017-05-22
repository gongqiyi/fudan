<?php
/**
 * @copyright Copyright (c) 2016 上海稻壳网络科技有限公司
 * @link http://www.dookay.com/
 * @create Created on 2016/12/15
 */

namespace home\controllers;
use Yii;
use yii\base\Controller;


/**
 * 接口控制器(核心方法不可删除)
 *
 * @author xiaopig <xiaopig123456@qq.com>
 * @since 1.0
 */
class ApiController extends Controller
{
    /**
     * 清空缓存
     * /api/del-cache.html
     * type=['config','category','site','fragment']
     * token={type+date('Ymdh')}
     */
    public function actionDelCache(){
        $request = Yii::$app->getRequest();

        if($request->getIsPost()){
            $type = $request->post('type');
            $token = $request->post('token');
            if(in_array($type,['config','category','site','fragment']) && Yii::$app->getSecurity()->validatePassword($type.date('Ymdh',time()),$token)){

                Yii::$app->getCache()->delete($type);
                return json_encode([
                    'status'=>1
                ]);
            }
        }

        return json_encode([
            'status'=>0
        ]);
    }
}