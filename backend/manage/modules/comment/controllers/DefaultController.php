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
use common\components\manage\PrototypeController;
use common\entity\models\CommentModel;
use common\entity\models\CommentReplyModel;
use common\entity\searches\CommentSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for CommentModel model.
 */

class DefaultController extends PrototypeController implements CurdInterface
{
    /**
     * Lists all BbsCommentModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $searchModel->model_id = $this->categoryInfo->model_id;
        $searchModel->node_id = Yii::$app->getRequest()->get('data_id');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->with(['userInfo']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'nodeInfo'=>$this->findNodeInfo($searchModel->node_id)
        ]);
    }

    /**
     * Updates an existing BbsCommentModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->success([Yii::t('common','Operation successful')]);
            }
            $this->error([Yii::t('common','Operation failed')]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BbsCommentModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel();
        $id = explode(',',$id);

        if($model->deleteAll(['id'=>$id])){

            // 删除评论回复
            CommentReplyModel::deleteAll(['comment_id'=>$id]);

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
     * Finds the BbsCommentModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CommentModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id = null)
    {
        $model = empty($id)? new CommentModel():CommentModel::findOne($id);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    /**
     * 查找node内容
     * @param $nodeId
     */
    protected function findNodeInfo($nodeId){
        $modelName = '\\common\\entity\\'.($this->categoryInfo->model->type == 0?"nodes":"models").'\\'.ucwords($this->categoryInfo->model->name).'Model';
        $model = new $modelName();
        return $model->findOne($nodeId);
    }


    public function actionSort($id){}

    public function actionCreate(){}
}