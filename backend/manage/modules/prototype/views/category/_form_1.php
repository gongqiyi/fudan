<?php
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $model
 * @var $categoryList
 * @var $modelList
 */

?>
<!-- tab开始 -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#tab-category-base" aria-controls="tab-category-base" role="tab" data-toggle="tab">基本选项</a></li>
    <li role="presentation"><a href="#tab-category-config" aria-controls="tab-category-config" role="tab" data-toggle="tab">栏目配置</a></li>
    <li role="presentation"><a href="#tab-category-seo" aria-controls="tab-category-seo" role="tab" data-toggle="tab">SEO设置</a></li>
</ul>
<!-- 表单开始 -->
<div class="panel panel-default form-data">
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id'=>'j_form',
            'options'=>['class' => 'form-horizontal tab-content'],
            'fieldConfig'=>['template'=>'{label}<div class="col-sm-17">{input}{error}{hint}</div>', 'labelOptions'=>['class'=>'col-sm-4 control-label']]
        ]); ?>
        <!-- 表单控件开始 -->
        <div class="tab-pane active" id="tab-category-base" role="tabpanel">
            <?= Html::activeInput('hidden', $model, 'type') ?>
            <?= $form->field($model, 'title')->textInput() ?>
            <?= $form->field($model, 'sub_title')->textInput() ?>
            <?= $form->field($model, 'pid')->dropDownList(ArrayHelper::merge(['0'=>'—顶级栏目—'],ArrayHelper::map($categoryList,'id','title')), ['class'=>'form-control','prety'=>true])?>
            <?= $form->field($model, 'thumb',[
                'template'=> '{label}<div class="col-sm-17"><div class="list-img list-img-multiple clearfix j_upload_single_img">{input}<ul class="upload_list"></ul><a class="upload upload_btn" href="javascript:;"><span class="iconfont">&#xe607;</span></a></div>{error}{hint}</div>'
            ])->hiddenInput(['class'=>'upload_input']);?>
            <?= $form->field($model, 'content')->textarea(['class'=>'j_editor', 'style'=>'width:100%;height:350px;'])?>
            <?= $form->field($model, 'link')->textInput()?>
        </div>
        <div class="tab-pane" id="tab-category-config" role="tabpanel">
            <?= $form->field($model, 'slug')->textInput()->hint('格式：" channel/news/list/…… "') ?>
            <?= $form->field($model, 'target')->textInput()->hint('例如：“<code>target="_blank"</code>”')?>
            <?php
            if($model->status === null) $model->status = 1;
            echo $form->field($model, 'status')->radioList([1=>'显示',0=>'隐藏'],['itemOptions'=>['labelOptions'=>['class'=>'radio-inline']]])->label('前台是否显示');
            ?>
            <?php
            if($model->isNewRecord) $model->layouts = 'main';
            echo $form->field($model, 'layouts')->textInput()->hint('不填表示禁用布局');
            ?>
            <?= $form->field($model, 'template')->textInput(['placeholder'=>'默认为：index'])->label('内容模板')?>
        </div>
        <div class="tab-pane" id="tab-category-seo" role="tabpanel">
            <?= $form->field($model, 'seo_title')->textInput()?>
            <?= $form->field($model, 'seo_keywords')->textInput()?>
            <?= $form->field($model, 'seo_description')->textarea(['rows'=>8,'class'=>'form-control resize-none'])?>
        </div>
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

