<?php
/**
 * @var $content
 */
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use yii\helpers\Html;

// bodyClass用于设置基础布局中 <body>标签的class
$this->params['bodyClass'] = null;

$this->beginContent(Yii::$app->layoutPath.'/base.php');

?>
<div class="wrapper clearfix">
    <div class="do-left-nav visible-lg-block visible-md-block">
        <div class="second-nav none">
            <ul id="s_nav">
                <?php $categorys = ArrayHelper::getChildes($this->context->categoryList,190); foreach ($categorys as $item) {
                    if ($item['pid'] != 190 || $item['status'] != 1) continue;
                    ?>
                    <li <?= $this->context->categoryInfo->id == $item['id'] ? 'class="active"' : '' ?>><a
                            href="<?= $this->generateCategoryUrl($item) ?>"><i></i><?= $item['title'] ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="logo"><img src="/images/logo.jpg" alt=""></div>
        <div id="j_nav">
            <ul class="nav-list pt-3">
                <?php $cate = []; foreach ($this->context->categoryList as $cates){
                    if($cates['pid'] || !$cates['status']) continue;
                    $cate[] = $cates;}
                    foreach ($cate as $k=>$item):
                ?>
                <li data-action="<?=UrlHelper::to(['site/nav','category_id'=>$item['id']])?>" class="<?=count($cate) == intval($k+1)?'last':''?> <?=($this->context->categoryInfo->id == $item['id'] || $this->context->categoryInfo->pid == $item['id'])?'active':''?>"><a href="<?=$this->generateCategoryUrl($item)?>"><?=$item['title']?></a></li>
                <?php endforeach;?>
            </ul>
            <div class="do-search">
                <div class="title">
                    <h4>Search Center</h4>
                    <p>搜索中心</p>
                </div>
                <form role="search" action="<?=$this->generateCategoryUrl(2)?>">
                    <div class="search">
                        <?php
                        // 获取筛选内容
                        $searches = Yii::$app->getRequest()->get('searches');
                        ?>
                        <?=Html::hiddenInput('searches[mid]',7)?>
                        <?=Html::textInput('searches[title]',ArrayHelper::getValue($searches,'title'),['placeholder'=>'请输入关键词'])?>
                        <button type="submit" class="iconfont icon-fangdajing"></button>
                    </div>
                </form>
                <p class="copyright"><?=$this->context->fragment->copyright?></p>
                <div class="bdsharebuttonbox pb-6">
                    <a href="#" class="bds_sqq iconfont icon-qq" data-cmd="sqq" title="分享到QQ好友"></a>
                    <a href="#" class="bds_tsina iconfont icon-weibo" data-cmd="tsina" title="分享到新浪微博"></a>
                    <a href="#" class="bds_weixin iconfont icon-weixin" data-cmd="weixin" title="分享到微信"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="do-box">
        <div class="do-top-bg"></div>
        <div class="container pt-6">
            <!--移动端导航-->
            <nav class="navbar navbar-default visible-xs-block">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">
                            <img src="/images/logo2.png" alt="">
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="<?=$this->generateCategoryUrl(1)?>">首页</a></li>
                            <?php $categoryList = ArrayHelper::tree($this->context->categoryList); foreach ($categoryList as $item):
                                if ($item['id'] == 1 || $item['status'] != 1) continue;?>
                                <li class="dropdown">
                                    <a href="<?=$this->generateCategoryUrl($item)?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$item['title']?> <span class="caret"></span></a>
                                    <?php if($item['child']):?>
                                        <ul class="dropdown-menu">
                                            <?php foreach ($item['child'] as $child):?>
                                                <li><a href="<?=$this->generateCategoryUrl($child)?>"><?=$child['title']?></a></li>
                                            <?php endforeach;?>
                                        </ul>
                                    <?php endif;?>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </nav>
            <?=$content?>
        </div>
        <div class="do-search visible-xs-block">
            <div class="title">
                <h4>Search Center</h4>
                <p>搜索中心</p>
            </div>
            <form role="search" action="<?=$this->generateCategoryUrl(2)?>">
                <div class="search">
                    <?php
                    // 获取筛选内容
                    $searches = Yii::$app->getRequest()->get('searches');
                    ?>
                    <?=Html::hiddenInput('searches[mid]',7)?>
                    <?=Html::textInput('searches[title]',ArrayHelper::getValue($searches,'title'),['placeholder'=>'请输入关键词'])?>
                    <button type="submit" class="iconfont icon-fangdajing"></button>
                </div>
            </form>
            <p class="copyright"><?=ArrayHelper::getValue($this->context->fragment,'copyright')?></p>
            <div class="bdsharebuttonbox pb-6">
                <a href="#" class="bds_sqq iconfont icon-qq" data-cmd="sqq" title="分享到QQ好友"></a>
                <a href="#" class="bds_tsina iconfont icon-weibo" data-cmd="tsina" title="分享到新浪微博"></a>
                <a href="#" class="bds_weixin iconfont icon-weixin" data-cmd="weixin" title="分享到微信"></a>
            </div>
        </div>
        <?php if(in_array($this->context->categoryInfo->id,[1,50])):?>
            <footer class="footer none">
                <div class="content pt-6">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-14">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3">
                                        <div class="situation-link">
                                            <h5>研究院概况</h5>
                                            <div class="list clearfix">
                                                <?php $cate = ArrayHelper::getChildes($this->context->categoryList,190); foreach (array_chunk($cate,4) as $group):?>
                                                    <ul class="pull-left">
                                                        <?php foreach ($group as $item):?>
                                                            <li><a href="<?=$this->generateCategoryUrl($item)?>"><?=$item['title']?></a></li>
                                                        <?php endforeach;?>
                                                    </ul>
                                                <?php endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="situation-link">
                                            <h5>科研队伍</h5>
                                            <div class="list clearfix">
                                                <?php $cate = ArrayHelper::getChildes($this->context->categoryList,196); foreach (array_chunk($cate,4) as $group):?>
                                                    <ul class="pull-left">
                                                        <?php foreach ($group as $item):?>
                                                            <li><a href="<?=$this->generateCategoryUrl($item)?>"><?=$item['title']?></a></li>
                                                        <?php endforeach;?>
                                                    </ul>
                                                <?php endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-md-offset-1">
                                        <div class="situation-link">
                                            <h5>科研平台</h5>
                                            <!--<div class="list clearfix">
                                                <ul class="pull-left">
                                                    <li><a href="#">小动物活体成像系统</a></li>
                                                    <li><a href="#">BUXCO无创动物肺功能仪</a></li>
                                                    <li><a href="#">激光共聚焦显微镜</a></li>
                                                    <li><a href="#">动物行为平台</a></li>
                                                    <li><a href="#">超高压效液相色谱仪</a></li>
                                                </ul>
                                                <ul class="pull-left">
                                                    <li><a href="#">流式细胞仪</a></li>
                                                    <li><a href="#">膜片钳电生理平台</a></li>
                                                </ul>
                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 clearfix">
                                <div class="contact-us pull-left mr-5">
                                    <h5>联系我们</h5>
                                    <p>全国统一联系热线</p>
                                    <p class="telephone"><?=ArrayHelper::getValue($this->context->fragment,'hotLine')?>  </p>
                                    <p class="mb-1"><?=ArrayHelper::getValue($this->context->fragment,'address')?></p>
                                    <p class="wangzhi"><?=ArrayHelper::getValue($this->context->fragment,'email')?></p>
                                </div>
                                <div class="weixin pull-left">
                                    <?=UrlHelper::getImgHtml(ArrayHelper::getValue($this->context->fragment,'wechat'))?>
                                    <p>扫码关注我们</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        <?php endif;?>
        <div class="do-bottom-bg"></div>
    </div>
    <div style="display: none">
        <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1261904129'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1261904129%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
    </div>
</div>
<?php $this->endContent(); ?>