<?php
/**
 * @var $dataDetail
 * @var $prevLink
 * @var $nextLink
 */

use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use yii\helpers\Html;
use yii\web\View;

// 通过 $this->findCategoryById(栏目id) 来获取栏目数据，返回对象
$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>
<?=$this->render('/layouts/_bread')?>
<div class="medical-wrapper news-detail-wrapper">
    <div class="news-detail">
        <div class="detail-con">
            <h1 class="ellipsis"><?=$dataDetail->title?></h1>
            <?=$dataDetail->content?>
        </div>
    </div>
</div>
<div class="dx-cut clearfix mb-4">
    <div class="left pull-left">
        <?php if(!empty($prevLink)){?><a class="prev" href="<?=$this->generateDetailUrl($prevLink)?>">上一篇：<?=$prevLink->title?></a><?php } ?>
        <?php if(!empty($nextLink)){?><a class="next" href="<?=$this->generateDetailUrl($nextLink)?>">下一篇：<?=$nextLink->title?></a><?php } ?>
    </div>
    <div class="right pull-right">
        <a href="<?= $this->generateCategoryUrl(intval($this->context->categoryInfo->id)) ?>" class="button">返回列表</a>
    </div>
</div>


