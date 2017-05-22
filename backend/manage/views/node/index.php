<?php
/**
 * @block topButton 顶部按钮
 * @var $searchModel
 * @var $dataProvider
 */

use manage\assets\ListAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = '节点管理';

ListAsset::register($this);
$this->registerJs("listApp.init();", View::POS_READY);
?>
<?php $this->beginBlock('topButton'); ?>
<?= Html::a('新增节点', ['create','pid'=>1,'level'=>2], ['class' => 'btn btn-primary']) ?>
<?php $this->endBlock(); ?>

<!-- 数据列表开始 -->
<div class="panel panel-default list-data">
    <div class="panel-body">
        <div class="table-responsive scroll-bar">
            <table class="table table-hover" id="list_data">
                <thead>
                <tr>
                    <td align="center" width="60"><?=Yii::t('common','Id')?></td>
                    <td>节点标题</td>
                    <td>节点名称</td>
                    <td align="center">状态</td>
                    <td align="center"><?=Yii::t('common','Operation')?></td>
                </tr>
                </thead>
                <tbody>
                <?php
                $dataList = \common\helpers\ArrayHelper::linear($dataProvider->models, '&nbsp;&nbsp;├&nbsp;',1);
                foreach($dataList as $item){ ?>
                    <tr class="level_<?=$item['count']?>" <?php if($item['count'] > 1){echo 'style="display:none;"';}?>>
                        <td align="center"><?=$item['id']?></td>
                        <td>
                            <?php if($item['hasChild']){?>
                                <span class="list-icon list-icon-ch spacing-<?=$item['count']?> j_list_tree" data-id="<?=$item['id']?>" data-level="<?=$item['count']?>"></span>
                            <?php }else{?>
                                <span class="list-icon list-icon-nch spacing-<?=$item['count']?>"></span>
                            <?php }?>
                            <a href="<?=Url::to(['update','id'=>$item['id']]);?>" class="text-primary"><?=Html::encode($item['title'])?></a>
                        </td>
                        <td><?=Html::encode($item['name'])?></td>
                        <td align="center">
                            <?php if($item['status'] == 1){?>
                                <?= Html::a('<span class="iconfont">&#xe62a;</span>', ['status', 'id' => $item['id']], ['class' => 'j_batch status-'.$item['id'],'data-action'=>'status','data-value'=>0,'title'=>Yii::t('common','Disable')]) ?>
                            <?php }else{?>
                                <?= Html::a('<span class="iconfont">&#xe625;</span>', ['status', 'id' => $item['id']], ['class' => 'j_batch status-'.$item['id'],'data-action'=>'status','data-value'=>1,'title'=>Yii::t('common','Enable')]) ?>
                            <?php }?>
                        </td>
                        <td class="opt" align="center">
                            <?= Html::a(Yii::t('common','Modify'), ['update', 'id' => $item['id']], ['class' => 'text-primary']) ?>
                            <?= Html::a(Yii::t('common','Delete'), ['delete','id' => $item['id']],['class'=>'j_batch','data-action'=>'del']) ?>
                            <?php if($item['level'] > 2){?>
                                <span class="label label-info">添加子节点</span>
                            <?php }else{?>
                                <?= Html::a('添加子节点', ['create','pid' => $item['id'],'level'=>$item['level']+1],['class'=>'btn btn-primary btn-xs']) ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?=empty($dataList)?'<p class="list-data-default">'.Yii::t('common','No Data Found !').'</p>':''?>
        </div>
    </div>
</div><!-- 数据列表结束 -->