<?php

// 通过 $this->findCategoryById(栏目id) 来获取栏目数据，返回对象
use common\helpers\UrlHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>

<?=$this->render('/layouts/_bread')?>
<?=$this->context->categoryInfo->content?>
<div class="talent-echelon">
    <div class="title">
        <h4>Talent echelon</h4>
        <p class="mb-4">人才梯队</p>
        <?php
        // 使用 $dataProvider->getModels() 查询获取数据列表
        $dataProvider->pagination = ['defaultPageSize'=>5,'pageSize'=>5];
        $dataList = $dataProvider->getModels();
        if (!empty($dataList)) {
            ?>
            <div class="row">
                <?php foreach ($dataList as $item) { ?>
                    <div class="col-lg-4 col-md-6 col-xs-20 col-sm-20">
                        <div class="pic">
                            <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                            <a href="<?= $this->generateDetailUrl($item) ?>" class="hover-effect none">
                                <div class="name"><span><?=$item->title?></span>/ <?=$item->position?></div>
                                <div class="major"><?=$item->direction?></div>
                                <div class="link">></div>
                            </a>
                        </div>
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
    </div>
</div>
<!--分页-->
<nav class="text-center dx-page">
    <?= \yii\widgets\LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'firstPageCssClass' => '',
        'prevPageCssClass' => 'prev',
        'firstPageLabel' => '',
        'prevPageLabel' => '<span class="iconfont icon-jiantou-copy-copy-copy" aria-hidden="true"></span>',

        'nextPageCssClass' => 'next',
        'lastPageCssClass' => '',
        'nextPageLabel' => '<span class="iconfont icon-jiantou-copy-copy-copy" aria-hidden="true"></span>',
        'lastPageLabel' => '',

        'activePageCssClass' => 'actives',
        'maxButtonCount' => 8,
        'disabledPageCssClass' => true,
        'options' => ['class' => 'pagination',]
    ]) ?>
</nav>