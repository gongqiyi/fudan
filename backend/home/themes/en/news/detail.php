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

$this->registerCssFile('@web/js/jquery.bxslider/jquery.bxslider.css');
$this->registerJsFile('@web/js/jquery.bxslider/jquery.bxslider.min.js',['depends' => [\home\assets\CommonAsset::className()]]);
$this->registerJs('
	$(".bxslider").bxSlider();
', View::POS_READY);
$this->registerCss('
    .bxslider img{margin:0 auto;}
');

// 通过 $this->findCategoryById(栏目id) 来获取栏目数据，返回对象
$parentCategory = $this->findCategoryById(55);
?>
<div class="container">
	<div class="row pt-1-5 clearfix">
		<aside class="col-xs-36 col-sm-9 col-md-9 pt-1">
			<div class="t-2">
				<h3><?=$parentCategory->title?></h3>
			</div>
			<ul class="nav-left">
				<?php
				// 通过使用 ArrayHelper::getChildes(所有栏目,父栏目Id)来获取子栏目
				$subCategoryList = ArrayHelper::getChildes($this->context->categoryList,$parentCategory->id);

				foreach ($subCategoryList as $item){
					if($item['pid'] != $parentCategory->id || $item['status'] != 1) continue;
					?>
					<li<?=$this->context->categoryInfo->id==$item['id']?' class="current"':''?>><a href="<?=$this->generateCategoryUrl($item)?>"><i></i><?=$item['title']?></a></li>
				<?php }?>
			</ul>
		</aside>
		<div class="col-xs-36 col-sm-27 col-md-27 pt-1">
			<ol class="breadcrumb">
				<li><a href="<?=$this->generateCategoryUrl(1)?>">index</a></li>
				<?php
				$actionName = Yii::$app->controller->action->id;
				foreach ($this->context->parentCategoryList as $item):
					if($this->context->categoryInfo->id == $item['id'] && $actionName != 'detail'):?>
						<li class="active"><?= $item['title'] ?></li>
					<?php else:?>
						<li>
							<a href="<?= $this->generateCategoryUrl($item) ?>"><?= $item['title'] ?></a>
						</li>
					<?php endif;?>
				<?php endforeach; if(Yii::$app->controller->action->id == 'detail'):?>
					<li class="active">详情</li>
				<?php endif;?>
			</ol>
			<div class="t-article">
				<h2><?=$dataDetail->title?></h2>
				<p><a class="more" href="<?=$this->generateCategoryUrl($this->context->categoryInfo)?>">返回 &gt;</a>时间：<?=date('Y-m-d H:i',$dataDetail->create_time)?> &nbsp;&nbsp; 浏览：<?=$dataDetail->views?>次</p>
			</div>
			<hr />
			<article class="cnt-article clearfix pb-2">

				<h3>缩略图</h3>
				<p>
					<?php
					// 使用UrlHelper::getImgHtml($imgData,$options)方法来生成图片缩略图或获取图片，直接返回img标签。$option 索引为0的表示缩略图配置，不写不生成缩略图。
                    echo UrlHelper::getImgHtml($dataDetail->thumb,['w/300/h/300/q/80/m/1','class'=>'img']);?>
				</p>

				<?php if(!empty($dataDetail->atlas)):?>
				<h3>图集</h3>
				<ul class="bxslider">
					<?php
					// 对于多图片，必须先用UrlHelper::fileDataHandle()方法进行数据处理，然后使用UrlHelper::getImgHtml()方法来获取相应图片
					foreach (UrlHelper::fileDataHandle($dataDetail->atlas) as $item){?>
						<li><?=UrlHelper::getImgHtml($item);?></li>
					<?php }?>
				</ul>
				<?php endif;?>

				<h3>详细内容</h3>
				<?=$dataDetail->content?>

				<?php if(!empty($dataDetail->attachment)):?>
                <h3>附件下载</h3>
                <p>
					<u>
						<a href="<?php
						// 使用UrlHelper::getFileItem()方法来获取附件地址，
						echo UrlHelper::getFileItem($dataDetail->attachment,'file')?>" target="_blank" title="点击下载">
							<?=UrlHelper::getFileItem($dataDetail->attachment,'title')?></a>
					</u>
				</p>
				<?php endif;?>
			</article>

			<?php if(!empty($prevLink)){?><p>上一条：<a class="text-primary" href="<?=$this->generateDetailUrl($prevLink)?>"><?=$prevLink->title?></a></p><?php } ?>
			<?php if(!empty($nextLink)){?><p>下一条：<a class="text-primary" href="<?=$this->generateDetailUrl($nextLink)?>"><?=$nextLink->title?></a></p><?php } ?>
		</div>
	</div>
</div>


