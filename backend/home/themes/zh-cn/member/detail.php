<?php
/**
 * @var $dataDetail
 * @var $prevLink
 * @var $nextLink
 */

use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\View;

// 通过 $this->findCategoryById(栏目id) 来获取栏目数据，返回对象
$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>
<?=$this->render('/layouts/_bread')?>
<div class="pediatrics-details pt-4">
    <div class="row">
        <div class="col-md-7">
            <div class="pic">
                <?=UrlHelper::getImgHtml($dataDetail->thumb,['alt'=>$dataDetail->title])?>
            </div>
        </div>
        <div class="col-md-13">
            <div class="content">
                <div class="title">
                    <h2><?=$dataDetail->title?></h2>
                    <small><?=$dataDetail->description?></small>
                </div>
                <?=$dataDetail->content?>
            </div>
        </div>
    </div>
</div>


