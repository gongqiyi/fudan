<?php

use common\helpers\ArrayHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>
<?=$this->render('/layouts/_bread')?>
<div class="medical-wrapper news-detail-wrapper">
    <div class="main-con" style="margin-bottom: 20px">
        <div>
            <!-- Nav tabs -->
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
        </div>
    </div>
    <div class="news-detail">
        <div class="detail-con">
            <?=$dataDetail->content?>
        </div>
    </div>
</div>