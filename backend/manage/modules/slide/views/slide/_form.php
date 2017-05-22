<?php
// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/3/31.
// +----------------------------------------------------------------------
use manage\assets\FormAsset;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $model
 * @var $categoryInfo
 * @var $modelList
 */
FormAsset::register($this);
\manage\assets\UeditorAsset::register($this);
$this->registerJsFile('@web/js/plugins/uploadUeditor.js',['depends' => [FormAsset::className()]]);
$this->registerJs('formApp.init();commonApp.formYiiAjax($("#j_form"));uploadUeditor.singleImage($(".j_upload_single_img"));slideFormInit('.($model->related_data_model?:0).');$("#slidemodel-related_data_model").change(function(){slideFormInit($(this).val())});function slideFormInit(_val){if(_val>0){$(".field-slidemodel-related_data_id").show();$(".field-slidemodel-link").hide();}else{$(".field-slidemodel-related_data_id").hide();$(".field-slidemodel-link").show();}}', View::POS_READY);
$this->registerCss('.field-slidemodel-related_data_id,.field-slidemodel-link{display:none;}');
?>
<?php $this->beginBlock('topButton'); ?>
<?= Html::a(Yii::t('common','Back List'), ['index','category_id'=>$categoryInfo->id], ['class' => 'btn btn-default j_goback']) ?>
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
        <?=Html::activeInput('hidden',$model,'category_id')?>

        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'thumb',[
            'template'=> '{label}<div class="col-sm-17"><div class="list-img list-img-multiple clearfix j_upload_single_img">{input}<ul class="upload_list"></ul><a class="upload upload_btn" href="javascript:;"><span class="iconfont">&#xe607;</span></a></div>{error}{hint}</div>'
        ])->hiddenInput(['class'=>'upload_input']);?>
        <?php if($categoryInfo->enable_mobile){
            echo $form->field($model, 'thumb_mobile',[
                'template'=> '{label}<div class="col-sm-17"><div class="list-img list-img-multiple clearfix j_upload_single_img">{input}<ul class="upload_list"></ul><a class="upload upload_btn" href="javascript:;"><span class="iconfont">&#xe607;</span></a></div>{error}{hint}</div>'
            ])->hiddenInput(['class'=>'upload_input']); }else{
            $model->thumb_mobile = null;
            echo Html::activeHiddenInput($model,'thumb_mobile');
        }?>
        <?= $form->field($model, 'related_data_model')->dropDownList(ArrayHelper::merge(['0'=>'--自定义链接--'],ArrayHelper::map($modelList,'id','title')), ['class'=>'form-control','prety'=>true])?>
        <?= $form->field($model, 'link')->textInput() ?>
        <?= $form->field($model, 'related_data_id')->textInput(['type'=>'number'])->hint('从内容管理列表中找到<span class="alert-danger">数据ID</span>填入，<span class="alert-danger">为空</span>表示生成所选择栏目URL') ?>
        <?= $form->field($model, 'description')->textarea(['rows'=>5,'class'=>'form-control resize-none'])->label('描述')?>
        <?= $form->field($model, 'create_time',['template'=>'{label}<div class="col-sm-17"><label class="input-group">{input}<span class="input-group-addon iconfont">&#xe62c;</span></label></div>'])->hiddenInput(['class'=>'j_date_piker'])->label('创建日期') ?>
        <!-- 表单控件结束 -->
        <div class="form-data-footer">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-14">
                    <?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('common','Back List').' <span class="st">&gt;</span>', ['index','category_id'=>$categoryInfo->id], ['class' => 'btn btn-link j_goback']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<!-- 表单结束 -->