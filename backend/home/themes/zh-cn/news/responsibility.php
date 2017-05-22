<?php

// 通过 $this->findCategoryById(栏目id) 来获取栏目数据，返回对象
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use yii\helpers\StringHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
$dataProvider->pagination = [
    'pageSize'=>0,
    'defaultPageSize'=>0
];
$dataList = $dataProvider->getModels();
?>
<div class="row dx-social">
    <?=$this->render('/layouts/_bread')?>
    <div class="social-responsibility mt-10">
        <?=$this->context->categoryInfo->content?>
        <ul class="year-pager mb-2 mt-7">
            <?php if($dataList) foreach ($dataList as $i=>$da):?>
                <li><a href="javascript:;" data-slide-index="<?=$i?>"><?=$da->title?></a></li>
            <?php endforeach;?>
        </ul>
        <div class="border"></div>
        <div class="dx-slider">
            <div class="j_slider">
                <?php if($dataList) foreach ($dataList as $k=>$item):?>
                    <div class="text-pic clearfix">
                        <div class="pic pull-left">
                            <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                        </div>
                        <div class="text pull-left">
                            <h2><?=$item->title?></h2>
                            <p><?=$item->description?></p>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('endBody');?>
    <script>
        $(function(){
            $(".j_slider").bxSlider({
                pagerCustom:".year-pager",
                auto:true
            })
        });
    </script>
<?php $this->endBlock();?>