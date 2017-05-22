<?php
/**
 * @var $model
 */

use manage\assets\FormAsset;
use yii\web\View;

$this->title = '新增模型';
FormAsset::register($this);
$this->registerJs("formApp.init();commonApp.formYiiAjax($('#j_form'));$('#prototypemodelmodel-type').change(function(){if($(this).val()==2){\$('#model_route').show();}else{\$('#model_route').hide();}});", View::POS_READY);
?>
<?= $this->render('_form', ['model' => $model]) ?>