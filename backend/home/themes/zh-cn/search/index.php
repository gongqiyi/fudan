<?php
/**
 * @var $searches
 * @var $dataProvider
 */
?>
<div class="container">
	<div class="row pt-1-5 clearfix">
		<div class="col-xs-48 pt-1">
			<ol class="breadcrumb">
				<li><a href="<?=$this->generateCategoryUrl(1)?>">首页</a></li>
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
				<li><span>关键字“<?=\common\helpers\ArrayHelper::getValue($searches,'title')?>”</span></li>
			</ol>
			<?php
			// 使用 $dataProvider->getModels() 获取数据列表
			$dataList = $dataProvider->getModels();
			if(!empty($dataList)){
				?>
				<ul class="list-news">
					<?php foreach($dataList as $item){?>
						<li><span><?=date('Y-m-d H:i',$item->create_time)?></span><a href="<?=$this->generateDetailUrl($item)?>"><?=$item->title?></a></li>
					<?php }?>
				</ul>
			<?php }else{?>
				<div class="text-center">
					<h3>没有找到数据</h3>
					<p>没有搜索到相关数据，搜索其他关键字试试吧！</p>
				</div>
			<?php }?>
			<div class="pageturn pt-3 clearfix">
				<?= \common\widgets\SLinkPager::widget([
					'pagination' => $dataProvider->pagination,
					'firstPageCssClass' => 'dx-list-ac',
					'prevPageCssClass' => 'prev',
					'firstPageLabel' => '首页',
					'prevPageLabel' => '上一页',

					'nextPageCssClass' => 'next',
					'lastPageCssClass' => 'dx-list-ac',
					'nextPageLabel' => '下一页',
					'lastPageLabel' => '尾页',

					'activePageCssClass'=>'current',
					'maxButtonCount' => 8,
					'disabledPageCssClass' => true,
					'options' => ['class' => 'pagination']
				])?>
			</div>
		</div>
	</div>
</div>