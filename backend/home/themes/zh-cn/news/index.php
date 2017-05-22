<?php

// 通过 $this->findCategoryById(栏目id) 来获取栏目数据，返回对象
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use yii\helpers\StringHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>

<?=$this->render('/layouts/_bread')?>
<div class="medical-wrapper news-wrapper">
    <div class="main-con news-main">
        <div>
            <ul class="nav service-tab news-tabs">
                <?php
                // 通过使用 ArrayHelper::getChildes(所有栏目,父栏目Id)来获取子栏目
                $subCategoryList = ArrayHelper::getChildes($this->context->categoryList, $parentCategory->id);

                foreach ($subCategoryList as $item) {
                    if ($item['pid'] != $parentCategory->id || $item['status'] != 1) continue;
                    ?>
                    <li <?= $this->context->categoryInfo->id == $item['id'] ? 'class="active"' : '' ?>><a
                            href="<?= $this->generateCategoryUrl($item) ?>"><i></i><?= $item['title'] ?></a></li>
                <?php } ?>
            </ul>
            <div class="pt-2">
                <?php
                // 使用 $dataProvider->getModels() 查询获取数据列表
                $dataList = $dataProvider->getModels();
                if (!empty($dataList)) {
                    ?>
                    <div class="grid">
                        <?php foreach ($dataList as $k=>$item) { ?>
                            <div class="grid-item grid-item--width<?=$k?'3':'4'?> clearfix">
                                <a href="<?= $this->generateDetailUrl($item) ?>">
                                    <div><?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?></div>
                                    <div class="news-list-txt">
                                        <p class="title ellipsis"><?=$item->title?></p>
                                        <p class="miaoshu ellipsis-2l">
                                            <?=StringHelper::truncate($item->description,120)?>
                                        </p>
                                        <p class="time"><i class="iconfont icon-biao"></i><span><?=date('Y - m - d',$item->create_time)?></span></p>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else {
                    ?>
                    <div class="text-center">
                        <h3>暂无数据</h3>
                        <p>没找到数据，去其他页面看看吧！</p>
                    </div>
                <?php } ?>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    'firstPageCssClass' => 'dx-list-ac',
                    'prevPageCssClass' => 'prev',
                    'firstPageLabel' => '首页',
                    'prevPageLabel' => '上一页',

                    'nextPageCssClass' => 'next',
                    'lastPageCssClass' => 'dx-list-ac',
                    'nextPageLabel' => '下一页',
                    'lastPageLabel' => '尾页',

                    'activePageCssClass' => 'current',
                    'maxButtonCount' => 8,
                    'disabledPageCssClass' => true,
                    'options' => ['class' => 'j_pagination none',]
                ]) ?>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('endBody');?>
    <script>
        var grid = document.querySelector('.grid');
        var msnry = new Masonry( grid, {
            columnWidth: 3
        });
    </script>
<?php $this->endBlock();?>