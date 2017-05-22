<?php
/**
 * @var $searchModel
 * @var $dataProvider
 */

use manage\assets\ListAsset;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = '评论回复管理';

ListAsset::register($this);
$this->registerJs("listApp.init();", View::POS_READY);
?>
<!-- 数据列表开始 -->
<div class="panel panel-default list-data">
    <div class="panel-body">
        <table class="table table-hover" id="list_data">
            <thead>
            <tr>
                <td width="60"><?=Yii::t('common','Select')?></td>
                <td><?=Yii::t('common','Id')?></td>
                <td>内容</td>
                <td>回复者</td>
                <td align="center">回复时间</td>
                <td align="center">状态</td>
                <td align="center"><?=Yii::t('common','Operation')?></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach($dataList = $dataProvider->models as $item){ ?>
                <tr>
                    <td><?= Html::checkbox('choose',false,['value'=>$item->id])?></td>
                    <td><?=$item->id?></td>
                    <td><?= Html::encode($item->content);?></td>
                    <td><span class="label label-info"><?=$item->userInfo->nickname?:$item->userInfo->username?></span></td>
                    <td align="center"><?=date('Y-m-d H:i',$item->create_time)?></td>
                    <td align="center">
                        <?php if($item->status == 1){?>
                            <?= Html::a('<span class="iconfont">&#xe62a;</span>', ['status' ,'id' => $item->id], ['class' => 'j_batch status-'.$item->id,'data-action'=>'status','data-value'=>0,'title'=>Yii::t('common','Disable')]) ?>
                        <?php }else{?>
                            <?= Html::a('<span class="iconfont">&#xe625;</span>', ['status', 'id' => $item->id], ['class' => 'j_batch status-'.$item->id,'data-action'=>'status','data-value'=>1,'title'=>Yii::t('common','Enable')]) ?>
                        <?php }?>
                    </td>
                    <td class="opt" align="center">
                        <?= Html::a(Yii::t('common','Delete'), ['delete','id' => $item->id],['class'=>'j_batch','data-action'=>'del']) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?=empty($dataList)?'<p class="list-data-default">'.Yii::t('common','No Data Found !').'</p>':''?>
    </div>
</div><!-- 数据列表结束 -->

<!-- 数据分页开始 -->
<nav class="nav-operation clearfix">
    <div class="tools">
        <a href="javascript:;" id="j_choose_all"><?=Yii::t('common','Select All')?></a>
        <a href="javascript:;" id="j_choose_reverse"><?=Yii::t('common','Select Invert')?></a>
        <a href="javascript:;" id="j_choose_empty"><?=Yii::t('common','Clears all')?></a>
        <span>|</span>
        <?= Html::a(Yii::t('common','Batch delete'), ['delete'],['class'=>'j_batch','data-action'=>'batchDel']) ?>
    </div>
    <?= Html::beginForm('', 'get', ['class' => 'form-inline pagination-go','id'=>'j_pagination_go']) ?>
    <div class="form-group">
        <label><?=Yii::t('common','Jump to')?></label>
        <?= Html::input('text', 'page', 1, ['class' => 'form-control']) ?>
        <label><?=Yii::t('common','Page')?></label>
    </div>
    <?= Html::endForm() ?>
    <?=LinkPager::widget(['pagination' => $dataProvider->pagination,'hideOnSinglePage'=>false,'firstPageLabel'=>'<span class="iconfont">&#xe624;</span>','prevPageLabel'=>'<span class="iconfont">&#xe61d;</span>','lastPageLabel'=>'<span class="iconfont">&#xe623;</span>','nextPageLabel'=>'<span class="iconfont">&#xe622;</span>']); ?>
</nav><!-- 数据分页结束 -->