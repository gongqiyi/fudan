<?php
// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/18.
// +----------------------------------------------------------------------

/**
 * bbs评论
 */

namespace manage\modules\comment\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\CommentReplyModel;
use common\entity\searches\CommentReplySearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * ReplyController implements the CRUD actions for CommentReplyModel model.
 */

class ReplyController extends ManageController implements CurdInterface
{
    /**
     * Lists all BbsCommentReplyModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->layout = 'base';

        $searchModel = new CommentReplySearch();
        $searchModel->comment_id = Yii::$app->request->get('comment_id');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with(['userInfo']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Deletes an existing BbsCommentReplyModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel();
        $id = explode(',',$id);

        if($model->deleteAll(['id'=>$id])){

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 状态设置
     * @param int|string $id
     * @return mixed|void
     */
    public function actionStatus($id){
        $model = $this->findModel($id);
        $id = explode(',',$id);

        if($model->updateAll(['status'=>Yii::$app->request->get('value',0)],['id'=>$id])){
            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * Finds the BbsCommentReplyModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CommentReplyModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new CommentReplyModel():CommentReplyModel::findOne($id);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    public function actionUpdate($id){}

    public function actionSort($id){}

    public function actionCreate(){}
}