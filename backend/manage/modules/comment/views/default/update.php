<?php
/**
 * @var $model
 */

use manage\assets\FormAsset;
use yii\helpers\Url;
use yii\web\View;

$this->title = '修改评论';
FormAsset::register($this);
$this->registerJs("formApp.init();commonApp.formYiiAjax($('#j_form'));", View::POS_READY);
?>
<?= $this->render('_form', ['model' => $model]) ?>