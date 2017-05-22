<?php
/**
 * @block topButton 顶部按钮
 * @var $dataList
 */

use manage\assets\ListAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = '站点管理';

ListAsset::register($this);
$this->registerJs("listApp.init();", View::POS_READY);
?>
<?php $this->beginBlock('topButton'); ?>
<?= Html::a('新增站点', ['create'], ['class' => 'btn btn-primary']) ?>
<?php $this->endBlock(); ?>

<!-- 数据列表开始 -->
<div class="panel panel-default list-data">
    <div class="panel-body">
        <div class="table-responsive scroll-bar">
            <table class="table table-hover" id="list_data">
                <thead>
                <tr>
                    <td><?=Yii::t('common','Id')?></td>
                    <td>站点名</td>
                    <td>标识</td>
                    <td>主题</td>
                    <td class="text-center">是否启用移动端</td>
                    <td class="text-center">是否默认</td>
                    <td align="center" width="60">状态</td>
                    <td align="center"><?=Yii::t('common','Operation')?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($dataList as $item){ ?>
                    <tr>
                        <td><?=$item->id?></td>
                        <td><a href="<?=Url::to(['update','id'=>$item->id]);?>" class="text-primary"><?=Html::encode($item->title)?></a></td>
                        <td><?=$item->slug?></td>
                        <td><?=$item->theme?></td>
                        <td align="center"><?=$item->enable_mobile?"启用":"禁用"?></td>
                        <td align="center"><?=Html::radio('is_default',$item->is_default,['value'=>$item->id,'class'=>'j_setDefault'])?></td>
                        <td align="center">
                            <?php if($item->is_enable == 1){?>
                                <?= Html::a('<span class="iconfont">&#xe62a;</span>', ['status', 'id' => $item->id], ['class' => 'j_batch status-'.$item->id,'data-action'=>'status','data-value'=>0,'title'=>Yii::t('common','Disable')]) ?>
                            <?php }else{?>
                                <?= Html::a('<span class="iconfont">&#xe625;</span>', ['status', 'id' => $item->id], ['class' => 'j_batch status-'.$item->id,'data-action'=>'status','data-value'=>1,'title'=>Yii::t('common','Enable')]) ?>
                            <?php }?>
                        </td>
                        <td class="opt" align="center">
                            <?= Html::a(Yii::t('common','Modify'), ['update', 'id' => $item->id], ['class' => 'text-primary']) ?>
                            <?php if($this->context->isSuperAdmin) echo Html::a(Yii::t('common','Delete'), ['delete','id' => $item->id],['class'=>'j_batch','data-action'=>'del']); ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?=empty($dataList)?'<p class="list-data-default">'.Yii::t('common','No Data Found !').'</p>':''?>
        </div>
    </div>
</div><!-- 数据列表结束 -->
<?php $this->beginBlock('endBlock');?>
<script>
    $(function () {
        $('.j_setDefault').change(function () {
            var $this = $(this);
            commonApp.fieldUpdateRequest($this,{
                url:'<?=Url::to(['set-default'])?>',
                data:{id:$this.val()}
            });
        });
    });
</script>
<?php $this->endBlock();?>
