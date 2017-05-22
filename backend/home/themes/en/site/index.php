<?php
/**
 * @var $slideList
 */
use common\helpers\UrlHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\View;

// 注册js资源
$this->registerJs('
$("#j_slide").responsiveSlides({
    auto:true,
    pager:true,
    speed:800,
    maxwidth:980
});
', View::POS_READY);
?>
<div class="slides-wrap">
    <!--幻灯片开始-->
    <div class="container">
        <ul id="j_slide" class="slides">
            <?php
            // 使用 $this->findAdList(广告分类Id) 来获取广告列表，广告分类id从“内容设计》广告设计”中获取。
            $slideList = $this->findAdList(4);
            foreach($slideList as $item){?>
            <li>
                <a href="<?=$item->link?>">
                    <?=UrlHelper::getImgHtml($item->thumb,['alt'=>Html::encode($item->title)]);?>
                </a>
            </li>
            <?php }?>
        </ul>
    </div>
    <!--幻灯片结束-->
</div>
<div class="container">
    <div class="row pt-2 clearfix">
        <div class="col-xs-36 col-sm-24 col-md-24">
            <div class="t-1 pt-1 pb-1-5">
                <h3>News</h3>
            </div>
            <ul class="list-news-index">
                <?php
                // 使用 $this->findFragment(栏目Id,[排序],false)->all() 获取碎片内容
                $newsList = $this->findFragment(55,[],false)->limit(6)->all();
                foreach($newsList as $item){
                ?>
                <li><a href="<?=$this->generateDetailUrl($item)?>"><?=StringHelper::truncate($item->title,20)?></a></li>
                <?php }?>
            </ul>
            <p class="more-index pt-1"><a href="<?=$this->generateCategoryUrl(55)?>">more</a></p>
        </div>
        <div class="col-xs-36 col-sm-12 col-md-12">
            <div class="t-1 pt-1 pb-1-5">
                <h3>Contact</h3>
            </div>
            <div class="map-index">
                <span>
                    <i class="icon-phone"></i>
                    <em><?=$this->context->fragment->hotLine?></em>
                </span>
            </div>
        </div>
    </div>
</div>
<?php
// 内容块示例
$this->beginBlock('endBody');?>
    <script>
        $(function () {
            // 这里是内容块，会出现在body标签之前，每个页面只允许出现一次。
        });
    </script>
<?php $this->endBlock();?>