<?php

namespace manage\controllers;

use common\components\CurdInterface;
use common\components\manage\ManageController;
use common\entity\models\SystemFileslogModel;
use common\entity\searches\SystemFileslogSearch;
use common\libs\Image;
use common\libs\Upload;
use Yii;
use yii\web\Response;


/**
 * 文件上传
 */

class UploadController extends ManageController implements CurdInterface
{

    private $uploadPath = '/uploads/';
    private $uploadRootPath;

    /**
     * 初始化
     */
    public function init()
    {
        parent::init();

        Yii::$app->getRequest()->enableCsrfValidation = false;

        $this->uploadRootPath = Yii::$app->basePath.'/..'.$this->uploadPath;
    }

    /**
     * 上传文件日志列表
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $searchModel = new SystemFileslogSearch();
        $searchModel->folder = $request->get('folder');
        $searchModel->name = $request->get('name');
        //$searchModel->ext = explode(',',$request->get('ext'));

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if($ext = $request->get('ext'))
            $dataProvider->query->andFilterWhere(['in','ext',$ext]);

        if ($request->isAjax){
            Yii::$app->response->format=Response::FORMAT_JSON;
            $dataProvider->query->asArray();
            $datalist = $dataProvider->models;
            foreach($datalist as $key=>$value){
                $datalist[$key]['thumb'] = json_decode($value['thumb'],true);
            }

            exit(json_encode(array(
                'datalist'=>$datalist,
                'pages'=>$dataProvider->pagination->getPageCount()
            )));
        }else{
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * Updates an existing SystemRoleModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = SystemFileslogModel::findOne($id);

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
     * 删除一条日志记录
     * @param int|string $id
     * @return mixed|void
     */
    public function actionDelete($id)
    {
        $model = new SystemFileslogModel();
        $id = explode(',',$id);

        $list_file = $model->find()->where(['id'=>$id])->select(['savepath','savename','thumb'])->asArray()->all();

        if($model->deleteAll(['id'=>$id])){

            // 删除物理文件
            foreach($list_file as $value){
                if(file_exists($_file = Yii::$app->basePath.'/..'.$value['savepath'].$value['savename'])) unlink($_file);
                if(!empty($value['thumb'])){
                    foreach(json_decode($value['thumb']) as $v){
                        if(file_exists($__file = Yii::$app->basePath.'/..'.$v->file)) unlink($__file);
                    }
                }
            }

            $this->success([Yii::t('common','Operation successful')]);
        }
        $this->error([Yii::t('common','Operation failed')]);
    }

    /**
     * 上传图片
     * $_POST['folder'] 文件上传所在的文件夹，不填默认上传在default文件夹
     * $_POST['thumb'] 不传默认生成系统设置缩略图，如果传值为字符串“false”不生成缩略图。格式:124*235*1=>宽*高*类型
     */
    public function actionImage(){

        $request = Yii::$app->request;

        if($request->isPost) {
            $folder = trim($request->post('folder'));
            $result = $this->uploadfile([
                'type'=>'image',
                'folder'=>$folder,
                'maxSize'=>$this->config['upload']['size'],
                'exts'=> array('jpg', 'gif', 'png', 'jpeg','bmp')
            ]);

            if(!$result[0]) {
                die('{"status" : 0, "error":"'.$result[1].'"}');
            }else{
                // 是否添加水印
                $isWater = false;
                if(intval($this->config['upload']['watermark_status'] == 1) && $this->config['upload']['watermark_path']) $isWater = true;

                $image = new Image();
                $resultFiles = [];
                $fileslog = new SystemFileslogModel();
                foreach($result[1] as $key => $file){
                    $_filepath = $this->uploadRootPath.$file['savepath'].$file['savename'];

                    // 检测缩略图文件夹是否存在
                    $thmub_path = $this->uploadRootPath.$file['savepath'].'thumb';

                    if(!is_dir($thmub_path))
                        mkdir($thmub_path);
                    $dir = dirname($thmub_path);
                    if(!is_dir($dir)){
                        mkdir($dir,0777,true);
                    }

                    //生成缩略图
                    $thumb_data = $this->getThumbData($request->post('thumb'));
                    foreach($thumb_data as $value){
                        $image->open($_filepath);

                        $image->thumb($value['width'],$value['height'],$value['type'])->save($thmub_path.'/'.$value['width'].'x'.$value['height'].'_'.$file['savename']);
                        $file['thumb'][] = [
                            'symbol'=>$value['width'].'x'.$value['height'],
                            'file'=>$this->uploadPath.$file['savepath'].'thumb/'.$value['width'].'x'.$value['height'].'_'.$file['savename'],
                            'width'=>round($image->width()),
                            'height'=>round($image->height())
                        ];
                    }
                    if(count($thumb_data) < 1) $file['thumb'] = [];

                    //添加水印
                    if($isWater){
                        $image->open($_filepath);
                        $water_position = intval($this->config['upload']['watermark_position']);
                        if($water_position == 0) $water_position =  rand(1,9);

                        $image->water($this->uploadRootPath.$this->config['upload']['watermark_path'],$water_position,intval($this->config['upload']['watermark_opacity']))->save($_filepath);
                    }

                    //图片高宽
                    $image->open($_filepath);
                    $file['width'] = round($image->width());
                    $file['height'] = round($image->height());

                    // 文件上传日志
                    $file['savepath'] = $this->uploadPath.$file['savepath'];
                    $fileslog->create($file, $folder);

                    $resultFiles[] = $file;
                }

                die('{"status" : 1, "file":'.json_encode(count($resultFiles) == 1?$resultFiles[0]:$resultFiles).'}');
            }
        }else{
            die('{"status" : 0, "error":"非法上传！"}');
        }
    }

    /**
     * 文件上传
     * @param $param
     * @return array|bool
     */
    private function uploadfile($param){
        $param['folder'] = $param['folder']?$param['folder']:$param['folder'] = 'default';

        $upload = new Upload();
        $upload->maxSize   =     $param['maxSize'] ;
        $upload->exts      =     $param['exts'];
        $upload->rootPath  =     $this->uploadRootPath;
        $upload->savePath  =     $param['type'].'s/'.$param['folder'].'/';

        // 上传文件
        $info = $upload->upload();
        return $info?[true,$info]:[false,$upload->getError()];
    }

    /**
     * 返回需要生成的缩略图数据
     * @param $thumbPost
     * @return array
     */
    private function getThumbData($thumbPost){
        $thumb = [];

        if(!is_string($thumbPost) || (is_string($thumbPost) && trim($thumbPost) == 'true')){
            $thumb_size = explode('*',trim($this->config['upload']['thumb_size']));
            $thumb[] = [
                'width'=>intval($thumb_size[0]),
                'height'=>intval($thumb_size[1]),
                'type'=>intval($this->config['upload']['thumb_type'])
            ];
        } elseif(is_string($thumbPost) && trim($thumbPost) != 'false'){
            foreach(explode(',',$thumbPost) as $value){
                $tmp = explode('*',$value);
                $thumb[] = [
                    'width'=>intval($tmp[0]),
                    'height'=>intval($tmp[1]),
                    'type'=>intval(array_key_exists(2,$tmp))?$tmp[2]:$this->config['upload']['thumb_type']
                ];
            }
        }
        return $thumb;
    }

    /**
     * @return mixed|void
     */
    public function actionCreate(){}

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function actionStatus($id){}

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function actionSort($id){}
}