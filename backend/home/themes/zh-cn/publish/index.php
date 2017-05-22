<?php
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use yii\helpers\StringHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>
<?= $this->render('/layouts/_bread')?>
<div class="research-results">
    <ul class="nav nav-tabs service-tab" role="tablist">
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
    <?php
    // 使用 $dataProvider->getModels() 查询获取数据列表
    $dataList = $dataProvider->getModels();
    if (!empty($dataList)) {
        ?>
        <div class="row">
            <?php foreach ($dataList as $item) { ?>
                <div class="col-lg-10 col-md-20 pic-text">
                    <div class="row">
                        <div class="col-md-6 pic">
                            <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                        </div>
                        <div class="col-md-14 text">
                            <h3><?=$item->title?></h3>
                            <small>作者： <?=$item->author?> &nbsp;&nbsp;&nbsp;出版信息： <?=$item->publish?></small>
                            <p><?=StringHelper::truncate($item->description,120)?></p>
                            <a href="<?= $this->generateDetailUrl($item) ?>" class="link">了解更多</a>
                        </div>
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
