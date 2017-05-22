<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $model
 * @var $form
 */
?>
<?php $this->beginBlock('topButton'); ?>
<?= Html::a(Yii::t('common','Back List'), ['index'], ['class' => 'btn btn-default j_goback']) ?>
<?php $this->endBlock(); ?>

<!-- 表单开始 -->
<div class="panel panel-default form-data">
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id'=>'j_form',
            'options'=>['class' => 'form-horizontal'],
            'fieldConfig'=>['template'=>'{label}<div class="col-sm-17">{input}{error}{hint}</div>', 'labelOptions'=>['class'=>'col-sm-4 control-label']]
        ]); ?>
        <!-- 表单控件开始 -->
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'type')->dropDownList($this->context->modelTypeList, ['class'=>'form-control'])?>
        <div class="form-group" id="model_route" <?php if($model->type != 2 ) {echo 'style="display: none;"';}?>>
            <label class="col-sm-4 control-label">后台模块路由</label>
            <div class="col-sm-17">
                <?=Html::activeInput('text',$model,'route',['class'=>'form-control'])?>
            </div>
        </div>
        <?= $form->field($model, 'description')->textarea(['rows'=>3,'class'=>'form-control resize-none']) ?>
        <?php
        if($model->is_login === null) $model->is_login = 0;
        echo $form->field($model, 'is_login')->radioList([1=>'需要',0=>'不需要'],['itemOptions'=>['labelOptions'=>['class'=>'radio-inline']]])->label('是否需要登录');
        ?>
        <!-- 表单控件结束 -->
        <div class="form-data-footer">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-14">
                    <?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton(Yii::t('common','Reset'), ['class' => 'btn btn-default']) ?>
                    <?= Html::a(Yii::t('common','Back List').' <span class="st">&gt;</span>', ['index'], ['class' => 'btn btn-link j_goback']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<!-- 表单结束 -->

