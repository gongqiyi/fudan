<?php
/**
 * @var $model
 * @var $formModel
 */

use manage\assets\FormAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = '反馈详情';
FormAsset::register($this);
$this->registerJs("formApp.init();
commonApp.formYiiAjax($('#j_form'),{
    success:function(result){
        if(result.status){
            commonApp.notify.success('恭喜您，操作成功！');
            setTimeout(function () {
                history.go(0);
            },1500)
        }else{
            commonApp.notify.error('很遗憾，操作失败！');
        }
    }
});
$('.j_delete').click(function(){
    var \$this = $(this);
    if(\$this.parents('ul').find('li').size() == 1){
        commonApp.notify.error('至少保留一条数据！');
        return false;
    }
    commonApp.dialog.warning('您确定要删除这条数据吗？',{
    confirm:function(){
      commonApp.fieldUpdateRequest(\$this,{
        loadingTxt:'正在删除，请稍后...',
        successTxt:'恭喜您，删除此条数据成功！',
        errorTxt:'很遗憾，删除此条数据失败！',
        successCallback:function(result){
          \$this.parents('li').remove();
        }
      });
    }
  });
  return false;
});

", View::POS_READY);

/**
 * 生成html
 * @param $label
 * @param $value
 * @return string
 */
function generateHtml($label,$value){
    return '<div class="form-group" style="margin-bottom: 0;">'.
    '<label class="control-label col-sm-4">'.$label.'</label><div class="col-sm-17"><div class="form-control-static">'.($value?:'——').'</div></div></div>';
}
?>

<?php $this->beginBlock('topButton'); ?>
<a href="javascript:history.go(-1);" class="btn btn-default"><?=Yii::t('common','Back List')?></a>
<?php $this->endBlock(); ?>

<div class="panel panel-default">
    <div class="panel-body form-horizontal">
        <?=generateHtml($model->getAttributeLabel('content'),'<pre style="margin-bottom: 0;">'.$model->content.'</pre>')?>
        <?=generateHtml($model->getAttributeLabel('create_time'),date('Y-m-d H:i',$model->create_time))?>

        <div class="form-group">
            <label class="control-label col-sm-4">回复</label>
            <div class="col-sm-17">
                <ul class="list-group" style="margin-bottom: 0;">
                    <?php foreach ($model->reply as $reply):?>
                        <li class="list-group-item"><a class="pull-right text-primary j_delete" href="<?=\yii\helpers\Url::to(['delete','model_id'=>$this->context->modelInfo->id,'id' => $reply->id])?>">删除</a><?=Html::encode($reply->content)?></li>
                    <?php endforeach;?>
                </ul>
                <button type="button" class="btn btn-primary" style="margin-top: 7px;" data-toggle="modal" data-target="#replyModal">回复</button>
            </div>
        </div>
    </div>
</div>

<!-- 回复弹出框 -->
<div class="modal fade" id="replyModal" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="replyModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="replyModal">回复</h4>
            </div>
            <?php
            $form = ActiveForm::begin([
                'id'=>'j_form',
                'action'=>\yii\helpers\Url::to(['create','model_id'=>$this->context->modelInfo->id]),
                'options'=>['class' => 'form-horizontal'],
                'fieldConfig'=>['template'=>'<div class="col-sm-24">{input}{hint}</div>', 'labelOptions'=>['class'=>'col-sm-4 control-label']]
            ]); ?>
            <div class="modal-body">
                <?=Html::activeHiddenInput($formModel,'pid',['value'=>$model->id])?>
                <?= $form->field($formModel, 'content')->textarea(['rows'=>5,'class'=>'form-control resize-none'])->label('回复')?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary">提交</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>