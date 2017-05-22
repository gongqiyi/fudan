<?php
/**
 * @block topButton 顶部按钮
 * @var $searchModel
 * @var $dataProvider
 * @var $nodeInfo
 */

use manage\assets\ListAsset;
use manage\helpers\UrlHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = '评论管理';
$this->params['subTitle'] = '('.$nodeInfo->title.')';

ListAsset::register($this);
$this->registerJs("listApp.init();", View::POS_READY);
?>
<!-- 搜索框开始 -->
<?php $form = ActiveForm::begin([
    'action' => [Yii::$app->controller->action->id],
    'method' => 'get',
    'options'=>['class' => 'form-inline search-data'],
]); ?>
    <!-- 表单控件开始 -->
    <?= Html::input('hidden','data_id',$nodeInfo->id)?>
    <?= Html::input('hidden','category_id',$nodeInfo->category_id)?>
    <?= $form->field($searchModel, 'content')->textInput()?>
    <?= $form->field($searchModel, 'status')->dropDownList([1=>'启用',0=>'禁用'], ['prompt'=>'—不限—'])?>
    <?=$form->field($searchModel, 'is_push')->dropDownList([1=>'已推荐',0=>'不推荐'], ['prompt'=>'—不限—'])?>
    <!-- 表单控件结束 -->
    <?= Html::submitButton(Yii::t('common','Filter'), ['class' => 'btn btn-info']) ?>
<?php ActiveForm::end(); ?><!-- 搜索框结束 -->

<?php $this->beginBlock('topButton'); ?>
<?= Html::a(Yii::t('common','Back List'), ['/prototype/node/index','category_id'=>$nodeInfo->category_id], ['class' => 'btn btn-default j_goback']) ?>
<?php $this->endBlock(); ?>

<!-- 数据列表开始 -->
<div class="panel panel-default list-data">
    <div class="panel-body">
        <div class="table-responsive scroll-bar">
            <table class="table table-hover" id="list_data">
                <thead>
                <tr>
                    <td width="60"><?=Yii::t('common','Select')?></td>
                    <td><?=Yii::t('common','Id')?></td>
                    <td>内容</td>
                    <td>评论者</td>
                    <td align="center">评论时间</td>
                    <td align="center">状态</td>
                    <td align="center"><?=Yii::t('common','Operation')?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($dataList = $dataProvider->models as $item){ ?>
                    <tr>
                        <td><?= Html::checkbox('choose',false,['value'=>$item->id])?></td>
                        <td><?=$item->id?></td>
                        <td><a href="<?=Url::to(['update','id'=>$item->id]);?>" class="text-primary"><?php echo StringHelper::truncate(Html::encode($item->content),30);if($item->is_push == 1){ echo '<span class="status-data"><em>推荐</em></span>';}?></a></td>
                        <td><?php if(!empty($item->userInfo)){?><a href="<?=Url::to(['/member/user/view','id'=>$item->user_id])?>" class="label label-info"><?=$item->userInfo->username?></a><?php }?></td>
                        <td align="center"><?=date('Y-m-d H:i',$item->create_time)?></td>
                        <td align="center">
                            <?php if($item->status == 1){?>
                                <?= Html::a('<span class="iconfont">&#xe62a;</span>', ['status' ,'id' => $item->id], ['class' => 'j_batch status-'.$item->id,'data-action'=>'status','data-value'=>0,'title'=>Yii::t('common','Disable')]) ?>
                            <?php }else{?>
                                <?= Html::a('<span class="iconfont">&#xe625;</span>', ['status', 'id' => $item->id], ['class' => 'j_batch status-'.$item->id,'data-action'=>'status','data-value'=>1,'title'=>Yii::t('common','Enable')]) ?>
                            <?php }?>
                        </td>
                        <td class="opt" align="center">
                            <?= Html::a('评论回复管理', ['reply/index', 'comment_id' => $item->id,'per-page'=>6], ['class' => 'text-primary j_dialog_open','data-size'=>'large']) ?>
                            <?= Html::a(Yii::t('common','Modify'), ['update', 'id' => $item->id], ['class' => 'text-primary']) ?>
                            <?= Html::a(Yii::t('common','Delete'), ['delete','id' => $item->id],['class'=>'j_batch','data-action'=>'del']) ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?=empty($dataList)?'<p class="list-data-default">'.Yii::t('common','No Data Found !').'</p>':''?>
        </div>
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
        <?= Html::a('&#xe62a;', ['status','value'=>1],['class'=>'iconfont j_batch','data-action'=>'batchStatus','data-value'=>1,'title'=>Yii::t('common','Batch enable')]) ?>
        <?= Html::a('&#xe625;', ['status','value'=>0],['class'=>'iconfont j_batch','data-action'=>'batchStatus','data-value'=>0,'title'=>Yii::t('common','Batch disable')]) ?>
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