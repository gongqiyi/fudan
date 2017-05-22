<?php
/**
 * @block topButton 顶部按钮
 * @var $searchModel
 * @var $dataProvider
 * @var $showCreateButton
 * @var $categoryInfo
 */

use common\helpers\ArrayHelper;
use manage\assets\ListAsset;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = '内容管理';
$pList = ArrayHelper::getParents($this->context->categoryList,$categoryInfo->id);
$this->params['subTitle'] = '('.implode(' / ',ArrayHelper::getColumn($pList,'title')).')';

ListAsset::register($this);
\manage\assets\ZtreeAsset::register($this);
$this->registerJs("listApp.init();", View::POS_READY);

$dataList = $dataProvider->models;

if(file_exists(Yii::$app->getModule('prototype')->getViewPath().'/node/_list_'.$categoryInfo->model->name.'.php')){
    echo $this->render('_list_'.$categoryInfo->model->name,['dataList'=>$dataList,'categoryInfo'=>$categoryInfo]);
}

$categoryList = ArrayHelper::index($this->context->categoryList,'id');
?>
<?php if($showCreateButton){ $this->beginBlock('topButton'); ?>
<?= Html::a('添加内容', ['create','category_id'=>$categoryInfo->id], ['class' => 'btn btn-primary']) ?>
<?php $this->endBlock(); }?>

<!-- 搜索框开始 -->
<?php $form = ActiveForm::begin([
    'action' => [Yii::$app->controller->action->id],
    'method' => 'get',
    'options'=>['class' => 'form-inline search-data'],
]); ?>
    <!-- 表单控件开始 -->
    <?= Html::input('hidden', 'category_id', $categoryInfo->id) ?>
    <?= $form->field($searchModel, 'title')->label('标题') ?>
    <?= $form->field($searchModel, 'status')->dropDownList([1=>'启用',0=>'禁用'], ['prompt'=>'—不限—','class'=>'form-control'])->label('状态') ?>
    <?= $form->field($searchModel, 'is_push')->dropDownList([1=>'推荐',0=>'不推荐'], ['prompt'=>'—不限—','class'=>'form-control'])->label('是否推荐') ?>
    <!-- 表单控件结束 -->
    <?= Html::submitButton(Yii::t('common','Filter'), ['class' => 'btn btn-info']) ?>
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
                    <td>标题</td>
                    <?php if (isset($this->blocks['thead'])): ?>
                        <?= $this->blocks['thead'] ?>
                    <?php endif; ?>
                    <td align="center" width="150">创建时间</td>
                    <td align="center" width="100">状态</td>
                    <td align="center" width="100">排序</td>
                    <td align="center" width="100">创建人</td>
                    <td align="center" width="100">最后更新人</td>
                    <td align="center" width="200"><?=Yii::t('common','Operation')?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($dataList as $i=>$item){ ?>
                    <tr>
                        <td><?= Html::checkbox('choose',false,['value'=>$item->id])?></td>
                        <td><?=$item->id?></td>
                        <td>
                            <a href="<?=Url::to(['update','category_id'=>$item->category_id,'id'=>$item->id]);?>" class="text-primary"><?=StringHelper::truncate(Html::encode($item->title),24)?></a>
                            <?php if(isset($item->is_push) && $item->is_push == 1){?><span class="status-data"><em>推荐</em></span><?php }?>
                        </td>
                        <?php if (isset($this->blocks['tbody'.$item->id])): ?>
                            <?= $this->blocks['tbody'.$item->id] ?>
                        <?php endif; ?>
                        <td align="center"><?=date('Y-m-d',$item->create_time)?></td>
                        <td align="center">
                            <?php if($item->status == 1){?>
                                <?= Html::a('<span class="iconfont">&#xe62a;</span>', ['status','category_id'=>$item->category_id, 'id' => $item->id], ['class' => 'j_batch status-'.$item->id,'data-action'=>'status','data-value'=>0,'title'=>Yii::t('common','Disable')]) ?>
                            <?php }else{?>
                                <?= Html::a('<span class="iconfont">&#xe625;</span>', ['status','category_id'=>$item->category_id, 'id' => $item->id], ['class' => 'j_batch status-'.$item->id,'data-action'=>'status','data-value'=>1,'title'=>Yii::t('common','Enable')]) ?>
                            <?php }?>
                        </td>
                        <td align="center">
                            <span class="sort j_sort">
                                <?php
                                $_tag = $i == 0 && ArrayHelper::getValue(Yii::$app->getRequest()->get(),'page',1) == 1?' disabled':'';
                                echo Html::tag('a',Html::tag('span','&#xe62e;',['class'=>'iconfont']),['class'=>'sort-up'.$_tag,'href'=>Url::to(['sort','category_id'=>$categoryInfo->id,'id'=>$item->id,'mode'=>1]),'title'=>'上移']);
                                $_tag = $i+1 == count($dataList) && ArrayHelper::getValue(Yii::$app->getRequest()->get(),'page',1) == $dataProvider->pagination->getPageCount()?' disabled':'';
                                echo Html::tag('a',Html::tag('span','&#xe62d;',['class'=>'iconfont']),['class'=>'sort-down'.$_tag,'href'=>Url::to(['sort','category_id'=>$categoryInfo->id,'id'=>$item->id,'mode'=>0]),'title'=>'下移']);
                                unset($_tag);
                                ?>
                            </span>
                        </td>
                        <td align="center"><?=empty($item->createUserInfo)?'--':$item->createUserInfo->username?></td>
                        <td align="center"><?=empty($item->updateUserInfo)?'--':$item->updateUserInfo->username?></td>
                        <td class="opt" align="center">
                            <?php if (isset($this->blocks['operate'.$item->id])): ?>
                                <?= $this->blocks['operate'.$item->id] ?>
                            <?php endif; ?>
                            <?php /*if($item->is_comment == 1){
                                echo Html::a('评论管理', ['/comment/default/index','category_id'=>$item->category_id, 'data_id' => $item->id], ['class' => 'text-primary']);
                            }else{
                                echo '<span class="text-info">评论管理</span>';
                            }*/?>
                            <?php
                            // 预览链接
                            $currCategory = $categoryList[$item->category_id];
                            $currCategory['expand'] = json_decode($currCategory['expand']);

                            if($currCategory['expand']->enable_detail){
                                $url = empty($currCategory['slug'])?'/category_'.$currCategory['id']:'/'.$currCategory['slug'];
                                if(!$this->context->siteInfo->is_default) $url = '/'.$this->context->siteInfo->slug.$url;
                                echo Html::a(Yii::t('common','Preview'), $url.'/'.$item->id.$this->context->config['site']['urlSuffix'],['target'=>'_blank']);
                            }else{
                                echo '<span class="text-muted">'.Yii::t('common','Preview').'</span>';
                            }
                            ?>
                            <span class="text-muted">|</span>
                            <?= Html::a(Yii::t('common','Modify'), ['update','category_id'=>$item->category_id, 'id' => $item->id], ['class' => 'text-primary']) ?>
                            <?= Html::a(Yii::t('common','Delete'), ['delete','category_id'=>$item->category_id,'id' => $item->id],['class'=>'j_batch','data-action'=>'del']) ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php echo empty($dataList)?'<p class="list-data-default">'.Yii::t('common','No Data Found !').'</p>':'';?>
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
        <?= Html::a(Yii::t('common','Batch delete'), ['delete','category_id'=>$categoryInfo->id],['class'=>'j_batch','data-action'=>'batchDel']) ?>
        <?= Html::a(Yii::t('common','Batch sort'), ['sort','category_id'=>$categoryInfo->id],['id'=>'j_sort_batch','data-depth'=>1,'data-pid'=>0,'data-empty'=>empty($dataList)?1:0]) ?>
        <?= Html::a(Yii::t('common','Batch move'), ['move','category_id'=>$categoryInfo->id],['id'=>'j_move_batch']) ?>
        <?= Html::a('&#xe62a;', ['status','category_id'=>$categoryInfo->id,'value'=>1],['class'=>'iconfont j_batch','data-action'=>'batchStatus','data-value'=>1,'title'=>Yii::t('common','Batch enable')]) ?>
        <?= Html::a('&#xe625;', ['status','category_id'=>$categoryInfo->id,'value'=>0],['class'=>'iconfont j_batch','data-action'=>'batchStatus','data-value'=>0,'title'=>Yii::t('common','Batch disable')]) ?>
    </div>
    <?= Html::beginForm('', 'get', ['class' => 'form-inline pagination-go','id'=>'j_pagination_go']) ?>
    <div class="form-group">
        <label><?=Yii::t('common','Jump to')?></label>
        <?= Html::input('text', 'page',Yii::$app->getRequest()->get('page',1), ['class' => 'form-control']) ?>
        <label><?=Yii::t('common','Page')?></label>
    </div>
    <div class="form-group">
        <label><?=Yii::t('common',',Per page')?></label>
        <?php
        $defaultPageSize = array_key_exists('page_size',Yii::$app->params)?Yii::$app->params['page_size']:15;
        echo Html::dropDownList('per-page',Yii::$app->getRequest()->get('per-page',$defaultPageSize),[$defaultPageSize=>$defaultPageSize,50=>50,100=>100,200=>200],['id'=>'j_pageSize','class'=>'form-control','style'=>'width:auto;'])?>
        <label><?=Yii::t('common','item')?></label>
    </div>
    <?= Html::endForm() ?>
    <?=LinkPager::widget(['pagination' => $dataProvider->pagination,'hideOnSinglePage'=>false,'firstPageLabel'=>'<span class="iconfont">&#xe624;</span>','prevPageLabel'=>'<span class="iconfont">&#xe61d;</span>','lastPageLabel'=>'<span class="iconfont">&#xe623;</span>','nextPageLabel'=>'<span class="iconfont">&#xe622;</span>']); ?>

</nav><!-- 数据分页结束 -->

<!-- 排序 -->
<script type="text/html" id="tpl_sort_batch">
    <div class="dd">
        <?=Html::hiddenInput('sort',implode(',',ArrayHelper::getColumn($dataList,'sort')),['class'=>'input-sort'])?>
        <ol class="dd-list">
        <?php foreach ($dataList as $item){?>
            <li class="dd-item" data-id="<?=$item->id?>">
                <div class="dd-handle"><?=$item->id.'：'.StringHelper::truncate($item->title,150)?></div>
            </li>
        <?php }unset($dataList);?>
        </ol>
        <form action="javascript:;" method="post">
            <?=Html::hiddenInput(Yii::$app->request->csrfParam,Yii::$app->request->csrfToken)?>
            <?=Html::hiddenInput('data',null,['class'=>'input-data'])?>
        </form>
    </div>
</script>
<input type="hidden" name="expand_nav" id="j_expand_nav" data-mid="<?=$categoryInfo->model_id?>" data-cid="<?=$categoryInfo->id?>" value="<?=Url::to(['/prototype/category/expand_nav','render'=>false])?>">