<?php

use common\helpers\ArrayHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>
<?=$this->render('/layouts/_bread')?>
<div class="medical-wrapper">
    <div class="character-detail-con clearfix">
        <div class="detail-title">
            <?=$parentCategory->content?>
            <div class="biaoti-btn text-center">
                <?php
                // 通过使用 ArrayHelper::getChildes(所有栏目,父栏目Id)来获取子栏目
                $subCategoryList = ArrayHelper::getChildes($this->context->categoryList, $parentCategory->id);

                foreach ($subCategoryList as $item) {
                    if ($item['pid'] != $parentCategory->id || $item['status'] != 1) continue;
                    ?>
                    <a href="<?= $this->generateCategoryUrl($item) ?>" <?= $this->context->categoryInfo->id == $item['id'] ? 'class="active"' : '' ?>><?= $item['title'] ?></a>
                <?php } ?>
            </div>
        </div>
        <?=$dataDetail->content?>
    </div>
</div>