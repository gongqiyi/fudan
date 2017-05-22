<?php
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
$dataProvider->pagination = [
    'pageSize'=>0,
    'defaultPageSize'=>0
];
$dataList = $dataProvider->getModels();
?>
<?=$this->render('/layouts/_bread')?>
<div class="institutions mt-4 mb-6">
    <?php if (!empty($dataList)) {?>
    <div class="row">
        <?php foreach ($dataList as $item):?>
            <div class="col-lg-5 col-md-6 col-sm-10">
                <div class="pic">
                    <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                    <div class="dx-modal"></div>
                    <div class="title">
                        <p><?=$item->title?></p>
                    </div>
                    <a href="<?= $this->generateDetailUrl($item) ?>" class="hover-title none">
                        <div class="vertical-center" style="width: 100%;padding: 0 36px;">
                        <p><?=$item->title?></p>
                        <p class="iconfont icon-jiantou-copy"></p>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php } else {
        ?>
        <div class="text-center">
            <h3>暂无数据</h3>
            <p>没找到数据，去其他页面看看吧！</p>
        </div>
    <?php } ?>
</div>