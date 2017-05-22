<?php
/**
 * @var $model
 * @var $roleList
 * @var $categoryInfo
 */

use common\helpers\ArrayHelper;
use manage\assets\FormAsset;
use manage\assets\UeditorAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = '更新内容';
$pList = ArrayHelper::getParents($this->context->categoryList,$categoryInfo->id);
$this->params['subTitle'] = '('.implode(' / ',ArrayHelper::getColumn($pList,'title')).')';
FormAsset::register($this);
UeditorAsset::register($this);
$this->registerJs("formApp.init();commonApp.formYiiAjax($('#j_form'));", View::POS_READY);
?>

<div class="panel panel-default form-data">
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id'=>'j_form',
            'options'=>['class' => 'form-horizontal'],
            'fieldConfig'=>['template'=>'{label}<div class="col-sm-17">{input}{error}{hint}</div>', 'labelOptions'=>['class'=>'col-sm-4 control-label']]
        ]); ?>
        <!-- 表单控件开始 -->
        <?= $form->field($model, 'title')->textInput()->label('标题') ?>
        <?= $form->field($model, 'content')->textarea(['class'=>'j_editor', 'style'=>'width:100%;height:450px;'])->label('内容')?>
        <!-- 表单控件结束 -->
        <div class="form-data-footer">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-14">
                    <?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>