<?php
/**
 * @copyright Copyright (c) 2017 上海稻壳网络科技有限公司
 * @link http://www.dookay.com/
 * @create Created on 2017/1/11
 */

namespace manage\controllers;
use common\components\manage\ManageController;
use common\entity\searches\SystemLogSearch;
use Yii;


/**
 * 日志管理
 *
 * @author xiaopig <xiaopig123456@qq.com>
 * @since 1.0
 */
class LogController extends ManageController
{

    /**
     * 日志列表
     * @return string
     */
    public function actionIndex(){
        $searchModel = new SystemLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}