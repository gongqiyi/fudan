<?php
// +----------------------------------------------------------------------
// | forgetwork
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/5/20.
// +----------------------------------------------------------------------

/**
 * 表单原型节点
 */

namespace manage\modules\prototype\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\PrototypeModelModel;
use common\entity\models\SystemLogModel;
use common\entity\nodes\InvestigateSearch;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FormController extends ManageController implements CurdInterface
{

    /**
     * @var object 表单模型信息
     */
    public $modelInfo;

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();

        $this->modelInfo = $this->getModelInfo(Yii::$app->getRequest()->get('model_id'));
    }

    /**
     * 数据列表
     * @param bool $export
     * @return string
     */
    public function actionIndex($export = false){
        $modelName = '\\common\\entity\\nodes\\'.ucwords($this->modelInfo->name).'Search';
        $searchModel = new $modelName();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->sort = [
            'defaultOrder' => [
                'id'=> SORT_DESC,
            ]
        ];

        if($export){
            $dataProvider->pagination = ['pageSize'=>0];
        }else{
            $dataProvider->pagination = ['pageSize'=>array_key_exists('page_size',Yii::$app->params)?Yii::$app->params['page_size']:15];
        }

        $render = $export?'renderPartial':'render';

        return $this->$render('index_'.$this->modelInfo->name, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'formModel'=>$this->findModel($this->modelInfo->name)
        ]);
    }

    /**
     * 数据详情
     * @param $id
     * @param string $layout
     * @return string
     */
    public function actionView($id,$layout = 'main'){
        $model = $this->findModel($this->modelInfo->name,$id);

        $this->layout = '/'.$layout;

        return $this->render('view_'.$this->modelInfo->name, [
            'model' => $model,
            'formModel'=>$this->findModel($this->modelInfo->name)
        ]);
    }

    /**
     * 状态
     * @param int|string $id
     * @return mixed|void
     */
    public function actionStatus($id){
        $model = $this->findModel($this->modelInfo->name,$id);
        $id = explode(',',$id);

        if($model->updateAll(['status'=>Yii::$app->request->get('value',0)],['id'=>$id])){

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 删除
     * @param int|string $id
     * @return mixed|void
     * @throws NotFoundHttpException
     */
    public function actionDelete($id){
        $model = $this->findModel($this->modelInfo->name,$id);
        $ids = explode(',',$id);

        if($model->deleteAll(['id'=>$ids])){
            if(in_array('pid',$model->attributes())){
                $model->deleteAll(['pid'=>$ids]);

                SystemLogModel::create('delete','在表单“'.$this->modelInfo->title.'”下删除了Id分别为“'.$id.'”的内容');
            }
            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 查找一个模型
     * @param $modelName
     * @param null $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($modelName,$id = null)
    {
        $modelName = '\\common\\entity\\nodes\\'.ucwords($modelName).'Model';
        $model = empty($id)? new $modelName():$modelName::findOne($id);
        if($model !== null){
            return $model;
        }else{
            throw new NotFoundHttpException(Yii::t('common','The requested page does not exist.'));
        }
    }

    /**
     * 左侧扩展菜单
     */
    public function actionExpand_nav(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dataList = PrototypeModelModel::find()->where(['type'=>1])->asArray()->all();

        return $this->renderPartial('expand_nav',['dataList'=>$dataList]);
    }

    /**
     * 返回模型信息
     * @param $modelId
     * @return PrototypeModelModel
     */
    protected function getModelInfo($modelId){
        return PrototypeModelModel::findOne($modelId);
    }

    /**
     * 回复
     */
    public function actionCreate()
    {
        $model = $this->findModel($this->modelInfo->name);
        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                $attributes = $model->attributes();
                if(in_array('status',$attributes)) $model->status = 1;
                if($model->save()){
                    // 更改父级状态
                    if(in_array('pid',$attributes) && in_array('status',$attributes)){
                        $parent = $this->findModel($this->modelInfo->name,$model->pid);
                        if($parent->status != 1){
                            $parent->status = 1;
                            $parent->save();
                        }
                    }

                    $this->success([Yii::t('common','Operation successful')]);
                }
            }
            $this->error([Yii::t('common','Operation failed'),'message'=>$model->getErrors()]);
        }

        return $this->render('create_'.$this->modelInfo->name, [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        // TODO: Implement actionUpdate() method.
    }

    public function actionSort($id)
    {
        // TODO: Implement actionSort() method.
    }
}