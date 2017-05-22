<?php
/**
 * @var $dataList
 */

use manage\assets\FormAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = '碎片管理';

FormAsset::register($this);
\manage\assets\UeditorAsset::register($this);
$this->registerJsFile('@web/js/plugins/uploadUeditor.js',['depends' => [FormAsset::className()]]);
$this->registerJs("formApp.init();commonApp.formYiiAjax($('#j_form'));", View::POS_READY);
?>

<!-- 表单开始 -->
<div class="panel panel-default form-data">
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id'=>'j_form',
            'options'=>['class' => 'form-horizontal'],
            'fieldConfig'=>['template'=>'{label}<div class="col-sm-17">{input}{error}{hint}</div>', 'labelOptions'=>['class'=>'col-sm-4 control-label']]
        ]); ?>
        <!-- 表单控件开始 -->
        <?php
        foreach ($dataList as $i => $item) {
            $setting = empty($item->setting)?[]:unserialize($item->setting);
            $control = $form->field($item, "[$i]value")->label($item->title);
            if(array_key_exists('hint',$setting)) $control->hint($setting['hint']);
            switch($item->style){
                case 1:
                    echo $control->textInput();
                    break;
                case 2:
                    echo $control->passwordInput();
                    break;
                case 3:
                    echo $control->textarea(['rows'=>array_key_exists('rows',$setting)?$setting['rows']:3,'class'=>'form-control resize-none']);
                    break;
                case 4:
                    echo $control->dropDownList(array_key_exists('list',$setting)?$setting['list']:[], array_merge(['class'=>'form-control'],$setting['other']));
                    break;
                case 5:
                    echo $control->radioList(array_key_exists('list',$setting)?$setting['list']:[],['itemOptions'=>['labelOptions'=>['class'=>'radio-inline']]]);
                    break;
                case 6:
                    echo $control->radioList(array_key_exists('list',$setting)?$setting['list']:[],['item'=>function($index, $label, $name, $checked, $value){
                        return '<div class="radio"><label><input type="radio" name="'.$name.'" value="'.$value.'"'.($checked==$value?' checked':'').'>'.$label.'</label></div>';
                    }]);
                    break;
                case 7:
                    echo $control->radioList(array_key_exists('list',$setting)?$setting['list']:[],['itemOptions'=>['labelOptions'=>['class'=>'checkbox-inline']]]);
                    break;
                case 8:
                    echo $control->radioList(array_key_exists('list',$setting)?$setting['list']:[],['item'=>function($index, $label, $name, $checked, $value){
                        return '<div class="checkbox"><label><input type="checkbox" name="'.$name.'" value="'.$value.'"'.($checked==$value?' checked':'').'>'.$label.'</label></div>';
                    }]);
                    break;
                case 9:
                    echo $form->field($item, "[$i]value",[
                        'template'=> '{label}<div class="col-sm-17"><div class="list-img list-img-multiple clearfix" id="j_upload_single_img'.$i.'">{input}<ul class="upload_list"></ul><a class="upload upload_btn" href="javascript:;"><span class="iconfont">&#xe607;</span></a></div>{error}{hint}</div>'
                    ])->hiddenInput(['class'=>'upload_input'])->label($item->title);
                    $this->registerJs('uploadUeditor.singleImage($("#j_upload_single_img'.$i.'"));', View::POS_READY);

                    break;
                case 10:
                    echo $form->field($item, "[$i]value",[
                        'template'=> '{label}<div class="col-sm-17"><div class="list-img list-img-multiple clearfix" id="j_upload_multiple_img'.$i.'">{input}<ul class="upload_list"></ul><a class="upload upload_btn" href="javascript:;"><span class="iconfont">&#xe607;</span></a></div>{error}{hint}</div>'
                    ])->hiddenInput(['class'=>'upload_input'])->label($item->title);
                    $this->registerJs('uploadUeditor.multipleImage($("#j_upload_multiple_img'.$i.'"));', View::POS_READY);
                    break;
                case 11:
                    echo $control->textInput(['id'=>'j_tag_input'.$i]);
                    $this->registerJs("$('#j_tag_input".$i."').tagsinput();", View::POS_READY);
                    break;
                default:
                    if (isset($this->blocks[$item->name])){
                        echo $this->blocks[$item->name];
                    }
                    break;
            }
        }
        ?>
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
<!-- 表单结束 -->