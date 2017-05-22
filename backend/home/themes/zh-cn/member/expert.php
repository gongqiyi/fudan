<?php

// 通过 $this->findCategoryById(栏目id) 来获取栏目数据，返回对象
use common\helpers\UrlHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>

<?=$this->render('/layouts/_bread')?>
<div class="expert-team">
    <?php
    // 使用 $dataProvider->getModels() 查询获取数据列表
    $dataList = $dataProvider->getModels();
    if (!empty($dataList)) {
        ?>
        <div class="row">
            <?php foreach ($dataList as $item) { ?>
                <div class="col-md-4 col-xs-20 col-sm-20">
                    <a data-action="<?=UrlHelper::to(['site/details','category_id'=>$item['category_id'],'id'=>$item['id']])?>" href="javascript:;" class="pic">
                        <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                        <div class="title"><?=$item->title?></div>
                    </a>
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

<?php $this->beginBlock('endBody');?>
<script>
    $(function () {
        //弹窗
        $(".expert-team .pic").on("click",function () {
            var $this = $(this);
            $.get($this.data('action'),function (re) {
                var $re = $(re);
                $('.j_detail').html($re.html());
                console.log($re.html());
                layer.open({
                    type:1,
                    title: false,
                    content: $('.dx-alert'),
                    area:["1000px"],
                    skin: 'dx-close'
                })
            });
        })
    });
</script>
<?php $this->endBlock();?>
