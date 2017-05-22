<?php
/**
 * @var $dataDetail
 * @var $prevLink
 * @var $nextLink
 */
use common\helpers\UrlHelper;

?>

<div class="dx-alert clearfix none j_detail">
    <div class="pic pull-left"><?=UrlHelper::getImgHtml($dataDetail->thumb,['alt'=>$dataDetail->title])?></div>
    <div class="text pull-left">
        <h3><?=$dataDetail->title?></h3>
        <?=$dataDetail->content?>
    </div>
</div>
