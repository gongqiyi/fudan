<?php
/**
 * @var $model
 * @var $categoryList
 * @var $modelList
 */

use manage\assets\FormAsset;
use manage\assets\UeditorAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = '更新栏目';
$this->params['subTitle'] = '('.$this->context->categoryTypeList[$model->type].')';

FormAsset::register($this);
UeditorAsset::register($this);
$this->registerJsFile('@web/js/plugins/uploadUeditor.js',['depends' => [FormAsset::className()]]);
$this->registerJs("formApp.init();commonApp.formYiiAjax($('#j_form'));uploadUeditor.singleImage($('.j_upload_single_img'));", View::POS_READY);
?>
<?php $this->beginBlock('topButton'); ?>
<?= Html::a(Yii::t('common','Back List'), ['index'], ['class' => 'btn btn-default j_goback']) ?>
<?php $this->endBlock(); ?>
<?= $this->render('_form_'.$model->type, ['model' => $model,'categoryList'=>$categoryList,'modelList'=>$modelList]) ?>