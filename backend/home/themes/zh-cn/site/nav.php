<?php
use common\helpers\ArrayHelper;
?>

<ul id="s_nav">
    <?php $categorys = ArrayHelper::getChildes($this->context->categoryList,$this->context->categoryInfo->id); foreach ($categorys as $item) {
        if ($item['pid'] != $this->context->categoryInfo->id || $item['status'] != 1) continue;
        ?>
        <li <?= $this->context->categoryInfo->id == $item['id'] ? 'class="active"' : '' ?>><a
                href="<?= $this->generateCategoryUrl($item) ?>"><i></i><?= $item['title'] ?></a></li>
    <?php } ?>
</ul>
