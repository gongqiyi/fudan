<?php
/**
 * @block topButton 顶部按钮
 * @var $searchModel
 * @var $dataProvider
 * @var $group
 * @var $categoryList
 * @var $modelList
 * @var $pid
 */

use common\helpers\ArrayHelper;
use manage\assets\ListAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * 获取node List视图
 * @param $siteInfo
 * @param $categoryList
 * @param $categoryInfo
 * @param string|null $defaultView
 * @return string
 */
function findNodeListView($siteInfo,$categoryList,$categoryInfo,$defaultView = 'index'){
    if($categoryInfo['type'] == 3) return '--';

    $parentCategoryList = ArrayHelper::getParents($categoryList,$categoryInfo['id']);

    $viewName = '';
    foreach(array_reverse($parentCategoryList,false) as $item){
        if(!empty($item['template'])){
            $viewName = $item['template'];
            break;
        }
    }
    if(strpos($viewName,'//') === 0){
        $view = str_replace('//','',$viewName);
    }else{
        switch($categoryInfo['type']){
            case 1:
                $view = 'page/'.(empty($viewName)?$defaultView:$viewName);
                break;
            case 2:
                if(empty($viewName)){
                    $view = $categoryInfo['slug_rules'];
                }else{
                    $temp = explode('/',$categoryInfo['slug_rules']);
                    $view = $temp[0].'/'.$viewName;
                }

                break;
            default:
                $view = $categoryInfo['model']['name'].'/'.(empty($viewName)?$defaultView:$viewName);
                break;
        }
    }

    return $siteInfo->theme.'/'.$view.'.php';
}

$this->title = '栏目管理';

ListAsset::register($this);
$this->registerJs("listApp.init();", View::POS_READY);
?>
<?php $this->beginBlock('topButton'); ?>
<div class="btn-group">
    <?= Html::button('新增栏目', ['class' => 'btn btn-primary dropdown-toggle','data-toggle'=>'dropdown']) ?>
    <ul class="dropdown-menu">
        <?php foreach($this->context->categoryTypeList as $i=>$item){?>
        <li><a href="<?=Url::to(['create','type'=>$i])?>"><?=$item?></a></li>
        <?php } ?>
    </ul>
</div>

<?php $this->endBlock(); ?>

<!-- 搜索框开始 -->
<?php $form = ActiveForm::begin([
    'action' => [Yii::$app->controller->action->id],
    'method' => 'get',
    'options'=>['class' => 'form-inline search-data'],
]); ?>
<!-- 表单控件开始 -->
<div class="form-group">
    <label class="control-label">父级栏目</label>
    <?= Html::dropDownList('pid', $pid, ArrayHelper::map($categoryList, 'id', 'title'),['prompt'=>'—不限—','class'=>'form-control']) ?>
    <div class="help-block"></div>
</div>
<?= $form->field($searchModel, 'status')->dropDownList([1=>'显示',0=>'隐藏'], ['prompt'=>'—不限—','class'=>'form-control'])->label('前台是否显示') ?>
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
                    <td align="center" width="60"><?=Yii::t('common','Id')?></td>
                    <td>栏目名称</td>
                    <td width="100">栏目类型</td>
                    <?php if($this->context->isSuperAdmin):?>
                    <td></td>
                    <?php endif;?>
                    <td>URL</td>
                    <?php if($this->context->isSuperAdmin):?>
                    <td>模板文件</td>
                    <?php endif;?>
                    <td align="center" width="100">前台是否显示</td>
                    <td align="center" width="200"><?=Yii::t('common','Operation')?></td>
                </tr>
                </thead>
                <tbody>
                <?php $dataList = ArrayHelper::linear($dataProvider->models, '&nbsp;&nbsp;├&nbsp;',$pid?:0);
                foreach($dataList as $item){ ?>
                    <tr class="level_<?=$item['count']?>" <?php if($item['count'] > 1){echo 'style="display:none;"';}?>>
                        <td><?= Html::checkbox('choose',false,['value'=>$item['id']])?></td>
                        <td align="center"><?=$item['id']?></td>
                        <td>
                            <?php if($item['hasChild']){?>
                                <span class="list-icon list-icon-ch spacing-<?=$item['count']?> j_list_tree" data-id="<?=$item['id']?>" data-level="<?=$item['count']?>"></span>
                            <?php }else{?>
                                <span class="list-icon list-icon-nch spacing-<?=$item['count']?>"></span>
                            <?php }?>
                            <a href="<?=Url::to(['update','id'=>$item['id']]);?>" class="text-primary"><?=Html::encode($item['title'])?></a>
                        </td>
                        <td><span class="label label-primary"><?=$this->context->categoryTypeList[$item['type']]?></span></td>
                    <?php if($this->context->isSuperAdmin):?>
                        <td><?=$item['model']["name"]?$item['model']["title"].'('.$item['model']["name"].')':'';?></td>
                    <?php endif;?>
                        <td style="font-family:'宋体';"><?php
                            if(empty($item['link'])){
                                $suffix = $this->context->config['site']['urlSuffix'];
                                switch ($item['type']){
                                    case 0:
                                    case 1:
                                        $url = empty($item['slug'])?'/category_'.$item['id'].$suffix:'/'.$item['slug'].$suffix;
                                        break;
                                    case 2:
                                        if($item['slug_rules'] == 'site/index'){
                                            $url = '/index'.$suffix;
                                        }else{
                                            $url = empty($item['slug'])?'/'.$item['slug_rules'].$suffix:'/'.$item['slug'].$suffix;
                                        }

                                        break;
                                    default:
                                        $url = $item['link'];
                                        break;
                                }
                            }else{
                                $url = $item['link'];
                            }
                            if(!$this->context->siteInfo->is_default && (stripos('#',$url,0) || stripos('javascript:',$url,0))) $url = '/'.$this->context->siteInfo->slug.$url;
                            echo $item['slug_rules'] == 'search/index'?'--':Html::a($url,$url,['target'=>'_blank']); ?>
                        </td>
                    <?php if($this->context->isSuperAdmin):?>
                        <td><?=findNodeListView($this->context->siteInfo,$dataList,$item)?></td>
                    <?php endif;?>
                        <td align="center">
                            <?php if($item['status'] == 1){?>
                                <?= Html::a('<span class="iconfont">&#xe62a;</span>', ['status', 'id' => $item['id']], ['class' => 'j_batch status-'.$item['id'],'data-action'=>'status','data-value'=>0,'title'=>'隐藏']) ?>
                            <?php }else{?>
                                <?= Html::a('<span class="iconfont">&#xe625;</span>', ['status', 'id' => $item['id']], ['class' => 'j_batch status-'.$item['id'],'data-action'=>'status','data-value'=>1,'title'=>'显示']) ?>
                            <?php }?>
                        </td>
                        <td class="opt" align="center">
                            <div class="btn-group">
                                <?= Html::button('新增子栏目', ['class' => 'btn btn-link dropdown-toggle','data-toggle'=>'dropdown']) ?>
                                <ul class="dropdown-menu">
                                    <?php foreach($this->context->categoryTypeList as $k=>$value){?>
                                        <li><a href="<?=Url::to(['create','type'=>$k,'pid'=>$item['id']])?>"><?=$value?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?= Html::a(Yii::t('common','Modify'), ['update', 'id' => $item['id']], ['class' => 'text-primary']) ?>

                            <?php if($this->context->isSuperAdmin) {if($item['id'] == 1){?>
                                <span class="text-muted"><?=Yii::t('common','Delete')?></span>
                            <?php }else{?>
                                <?= Html::a(Yii::t('common','Delete'), ['delete','id' => $item['id']],['class'=>'j_batch','data-action'=>'del']) ?>
                            <?php }}?>
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
        <?= Html::a(Yii::t('common','Batch sort'), ['sort'],['id'=>'j_sort_batch','data-pid'=>0,'data-empty'=>empty($dataList)?1:0]) ?>
        <?= Html::a('&#xe62a;', ['status','value'=>1],['class'=>'iconfont j_batch','data-action'=>'batchStatus','data-value'=>1,'title'=>Yii::t('common','Batch enable')]) ?>
        <?= Html::a('&#xe625;', ['status','value'=>0],['class'=>'iconfont j_batch','data-action'=>'batchStatus','data-value'=>0,'title'=>Yii::t('common','Batch disable')]) ?>
    </div>
</nav><!-- 数据分页结束 -->

<!-- 排序 -->
<script type="text/html" id="tpl_sort_batch">
    <div class="dd">
        <?=Html::hiddenInput('sort',implode(',',ArrayHelper::getColumn($dataList,'sort')),['class'=>'input-sort'])?>
        <ol class="dd-list">
            <?php
            function sortHtml($data, $pid = 0, $count = 0){
                $_html = '';
                foreach($data as $key=>$value){
                    // 生成li
                    $_html .= '<li class="dd-item" data-id="'.$value['id'].'"><div class="dd-handle">'.$value['id'].'：'.$value['title'].'</div>';
                    if($value['pid'] == $pid){
                        $_html .= sortHtml($value['child'],$value['id'],$count+1);
                    }
                    $_html .='</li>';
                }

                return $_html?($pid==0?$_html:'<ol class="dd-list">'.$_html.'</ol>'):'';
            }
            echo sortHtml(ArrayHelper::tree($dataList));
            unset($dataList);?>
        </ol>
        <form action="javascript:;" method="post">
            <?=Html::hiddenInput(Yii::$app->request->csrfParam,Yii::$app->request->csrfToken)?>
            <?=Html::hiddenInput('data',null,['class'=>'input-data'])?>
        </form>
    </div>
</script>