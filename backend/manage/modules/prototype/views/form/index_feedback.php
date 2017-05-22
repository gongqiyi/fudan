<?php
/**
 * @block topButton 顶部按钮
 * @var $searchModel
 * @var $dataProvider
 * @var $showCreateButton
 * @var $categoryInfo
 * @var $formModel
 */

use manage\assets\ListAsset;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

// 数据导出
if(Yii::$app->getRequest()->get('export',false)){
    $columns = [];
    foreach ($searchModel->getAttributes() as $i=>$item){
        if($i == 'id') continue;
        $columns[$i] = ['attribute'=>$i];
        if(strpos($i,'_time')) $columns[$i]['format'] = 'date';
    }

    \moonland\phpexcel\Excel::export([
        'models' => $dataProvider->getModels(),
        'format'=>'Excel5',
        'fileName'=>date('Ymd',time()).'_'.$this->context->modelInfo->title.'_v'.substr(time(),5),
        'columns' => ['content',
            'create_time'=>['attribute'=>'create_time','value'=>function($model){ return date('Y/n/j',$model->create_time); }],
        ],
    ]);
    exit;
}

$this->title = '反馈管理';

ListAsset::register($this);
$this->registerJs("listApp.init();", View::POS_READY);
?>

<!-- 搜索框开始 -->
<?php $form = ActiveForm::begin([
    'method' => 'get',
    'options'=>['class' => 'form-inline search-data'],
]); ?>
<?= Html::input('hidden', 'model_id', $this->context->modelInfo->id) ?>
<?= Html::input('hidden', 'export', false,['id'=>'exportInput']) ?>
<!-- 表单控件开始 -->
<?= $form->field($searchModel, 'content') ?>
<?= $form->field($searchModel, 'status')->dropDownList([1=>'已回复',0=>'未回复'], ['prompt'=>'—不限—','class'=>'form-control'])->label('状态') ?>
<!-- 表单控件结束 -->
<div class="form-group search-time">
    <label class="control-label">提交日期</label>
    <div class="input-group" style="width: 135px;">
        <?=Html::activeHiddenInput($searchModel,'searchStartTime',['class'=>'j_date_piker'])?>
        <span class="input-group-addon iconfont">&#xe62c;</span>
    </div>
    ~
    <div class="input-group" style="width: 135px;">
        <?=Html::activeHiddenInput($searchModel,'searchEndTime',['class'=>'j_date_piker'])?>
        <span class="input-group-addon iconfont">&#xe62c;</span>
    </div>
</div>
<?= Html::submitButton(Yii::t('common','Filter'), ['class' => 'btn btn-info']) ?>
<?= Html::button('导出', ['id'=>'j_export','class' => 'btn btn-primary','style'=>'margin-left:10px;']) ?>
<?php ActiveForm::end(); ?><!-- 搜索框结束 -->

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
                    <td align="center" width="150">创建时间</td>
                    <td align="center" width="100">状态</td>
                    <td align="center" width="200"><?=Yii::t('common','Operation')?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($dataList = $dataProvider->models as $item){ ?>
                    <tr>
                        <td><?= Html::checkbox('choose',false,['value'=>$item->id])?></td>
                        <td><?=$item->id?></td>
                        <td>
                            <a href="<?=Url::to(['view','model_id'=>$this->context->modelInfo->id,'id'=>$item->id]);?>" class="text-primary"><?=StringHelper::truncate(Html::encode($item->content),20)?></a>
                        </td>
                        <td align="center"><?=date('Y-m-d',$item->create_time)?></td>
                        <td align="center"><label class="label label-<?=$item->status?'success':'info'?>"><?=$item->status?'已回复':'未回复'?></label></td>
                        <td class="opt" align="center">
                            <?= Html::a('查看详情', ['view','model_id'=>$this->context->modelInfo->id,'id' => $item->id], ['class' => 'text-primary']) ?>
                            <?= Html::a(Yii::t('common','Delete'), ['delete','model_id'=>$this->context->modelInfo->id,'id' => $item->id],['class'=>'j_batch','data-action'=>'del']) ?>
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
        <?= Html::a(Yii::t('common','Batch delete'), ['delete','model_id'=>$this->context->modelInfo->id],['class'=>'j_batch','data-action'=>'batchDel']) ?>
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