<?php
/**
 * @var $model
 * @var $form
 * @var $categoryInfo
 * @var $parentCategoryList
 */
use common\helpers\ArrayHelper;
use manage\assets\FormAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->registerJsFile('@web/js/plugins/uploadUeditor.js',['depends' => [FormAsset::className()]]);
$this->registerJs('uploadUeditor.singleImage($(".j_upload_single_img"));uploadUeditor.multipleImage($(".j_upload_multiple_img"));', View::POS_READY);
?>

<?= $form->field($model, 'position')->textInput() ?>
<?= $form->field($model, 'direction')->textInput() ?>

<?= $form->field($model, 'thumb',[
    'template'=> '{label}<div class="col-sm-17"><div class="list-img list-img-multiple clearfix j_upload_single_img">{input}<ul class="upload_list"></ul><a class="upload upload_btn" href="javascript:;"><span class="iconfont">&#xe607;</span></a></div>{error}{hint}</div>'
])->hiddenInput(['class'=>'upload_input']);?>

<?= $form->field($model, 'content')->textarea(['class'=>'j_editor', 'style'=>'width:100%;height:350px;'])?>
<?= $form->field($model, 'description')->textarea(['rows'=>4,'class'=>'form-control resize-none','placeholder'=>'如果为空，系统会自动从内容中提取。'])?>

<?= $form->field($model, 'attachment',[
    'template'=> '{label}<div class="col-sm-17"><div class="list-file clearfix j_upload_single_file">{input}<ul class="upload_list"></ul><a class="upload btn btn-default upload_btn" href="javascript:;">文件上传</a></div>{error}{hint}</div>'
])->hiddenInput(['class'=>'upload_input'])?>
