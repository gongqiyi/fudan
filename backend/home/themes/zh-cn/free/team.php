<?php
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>
<?=$this->render('/layouts/_bread')?>
<div class="medical-wrapper">
    <div class="character-con clearfix">
        <?php
        // 通过使用 ArrayHelper::getChildes(所有栏目,父栏目Id)来获取子栏目
        $subCategoryList = ArrayHelper::getChildes($this->context->categoryList, $this->context->categoryInfo->id);

        $cate = [];
        foreach ($subCategoryList as $cates) {
            if ($cates['pid'] != $this->context->categoryInfo->id || $cates['status'] != 1) continue;
            $cate[] = $cates;}
            foreach ($cate as $k=>$item){
            ?>
            <a href="<?= $this->generateCategoryUrl($item) ?>" class="col-lg-5">
                <div class="con-img">
                    <?=UrlHelper::getImgHtml($item['thumb'],['alt'=>$item['title']])?>
                    <p class="up-img-wenzi"><?=$item['title']?></p>
                    <div class="img-bg text-center">
                        <img src="/images/service-character-bg.png" alt="">
                        <div class="bg-weizi">
                            <p><?=$item['sub_title']?></p>
                            <i class="iconfont icon-jiantou-copy"></i>
                        </div>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>