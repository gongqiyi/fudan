<?php
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
?>
<div class="dx-content-one">
    <div class="row">
        <div class="col-md-14">
            <div class="dx-index-show">
                <div class="index-show j_index-slider">
                    <?php
                    // 使用 $this->findAdList(广告分类Id) 来获取广告列表，广告分类id从“内容设计》广告设计”中获取。
                    $slideList = $this->findAdList(3);
                    foreach($slideList as $item){?>
                        <div class="pic">
                            <?=UrlHelper::getImgHtml($item->thumb,['alt'=>Html::encode($item->title)]);?>
                            <div class="dx-modal">
                                <div class="title">
                                    <h2><?=UrlHelper::getFileItem($item->thumb,'alt')?></h2>
                                    <small><?=date('Y - m - d',$item->create_time)?></small>
                                    <p><?=$item->description?></p>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="latest-papers">
                <div class="title" role="tablist">
                    <a href="#NoticeAnnouncement" class="papers actives" aria-controls="NoticeAnnouncement" role="tab" data-toggle="tab">
                        <h4>Notice Announcement</h4>
                        <p>通知公告</p>
                    </a>
                    <a href="#ScientificResearchTrends" class="active" aria-controls="ScientificResearchTrends" role="tab" data-toggle="tab">
                        <h4>Scientific Research Trends</h4>
                        <p>科研动态</p>
                    </a>
                    <a href="<?=$this->generateCategoryUrl(220)?>" class="more">More</a>
                </div>
                <div class="tab-content">
                    <div class="dx-tab tab-pane active" role="tabpanel" id="NoticeAnnouncement">
                        <div class="row">
                            <div class="col-md-10 col-sm-20 col-xs-20 dx-text-pic">
                                <div class="j_tab-slider">
                                    <?php
                                    // 使用 $this->findFragment(栏目Id,[排序],false)->all() 获取碎片内容
                                    $pushNews = $this->findFragment(220,[],false)->andWhere(['is_push'=>1])->limit(5)->all();
                                    $newNews = $this->findFragment(220,[],false)->andWhere(['is_push'=>0])->limit(5)->all();
                                    ?>
                                    <?php foreach ($pushNews as $item):?>
                                        <div class="text-pic">
                                            <div class="pic">
                                                <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                                                <div class="dx-modal"></div>
                                            </div>
                                            <div class="text clearfix pt-3">
                                                <div class="time pull-left">
                                                    <p><?=date('d - m',$item->create_time)?></p>
                                                    <p class="year"><?=date('Y',$item->create_time)?></p>
                                                </div>
                                                <div class="title pull-left">
                                                    <?=$item->title?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                            <div class="col-md-10 col-sm-20 col-xs-20 dx-news-list">
                                <ul class="news-list">
                                    <?php foreach ($newNews as $k=>$item):?>
                                        <li><a href="<?=$this->generateDetailUrl($item)?>" class="<?=intval($k+1)==count($pushNews)?'last':'clearfix'?>"><?=StringHelper::truncate($item->title,15)?><span><?=date('Y - m - d',$item->create_time)?></span></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="dx-tab tab-pane" role="tabpanel" id="ScientificResearchTrends">
                        <div class="row">
                            <div class="col-md-10 col-sm-20 col-xs-20 dx-text-pic">
                                <div class="j_tab-slider">
                                    <?php
                                    // 使用 $this->findFragment(栏目Id,[排序],false)->all() 获取碎片内容
                                    $pushNews = $this->findFragment(221,[],false)->andWhere(['is_push'=>1])->limit(5)->all();
                                    $newNews = $this->findFragment(221,[],false)->andWhere(['is_push'=>0])->limit(5)->all();
                                    ?>
                                    <?php foreach ($pushNews as $item):?>
                                        <div class="text-pic">
                                            <div class="pic">
                                                <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                                                <div class="dx-modal"></div>
                                            </div>
                                            <div class="text clearfix pt-3">
                                                <div class="time pull-left">
                                                    <p><?=date('d - m',$item->create_time)?></p>
                                                    <p class="year"><?=date('Y',$item->create_time)?></p>
                                                </div>
                                                <div class="title pull-left">
                                                    <?=$item->title?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                            <div class="col-md-10 col-sm-20 col-xs-20 dx-news-list">
                                <ul class="news-list">
                                    <?php foreach ($newNews as $k=>$item):?>
                                        <li><a href="<?=$this->generateDetailUrl($item)?>" class="<?=intval($k+1)==count($pushNews)?'last':'clearfix'?>"><?=$item->title?><span><?=date('Y - m - d',$item->create_time)?></span></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 dx-content-one-right">
            <div class="Research-Institutions">
                <div class="title">
                    <a href="<?=$this->generateCategoryUrl(193)?>" class="Research mb-4">
                        <h4>Research Institutions</h4>
                        <p>研究机构</p>
                    </a>
                    <a href="<?=$this->generateCategoryUrl(193)?>" class="more">More</a>
                </div>
                <?php
                // 使用 $this->findFragment(栏目Id,[排序],false)->all() 获取碎片内容
                $newsList = $this->findFragment(193,[],false)->all();
                $first = ArrayHelper::index($newsList,'id'); ?>
                <div class="Institutions">
                    <ul class="clearfix">
                        <li><a href="<?=$this->generateDetailUrl(ArrayHelper::getValue($first,'6'))?>">
                                <h5>针灸研究所</h5>
                                <p class="iconfont icon-zhenjiuke"></p>
                            </a></li>
                        <li><a href="<?=$this->generateDetailUrl(ArrayHelper::getValue($first,'5'))?>">
                                <h5>神经病学研究所</h5>
                                <p class="iconfont icon-shenjingxitong"></p>
                            </a></li>
                        <li><a href="<?=$this->generateDetailUrl(ArrayHelper::getValue($first,'8'))?>">
                                <h5>药物研究所</h5>
                                <p class="iconfont icon-yaowudrugs4"></p>
                            </a></li>
                        <li><a href="<?=$this->generateDetailUrl(ArrayHelper::getValue($first,'3'))?>" class="theory">
                                <h5>基础理论与 <br>
                                    应用研究所</h5>
                                <p class="iconfont icon-icon"></p>
                            </a></li>
                        <li><a href="<?=$this->generateDetailUrl(ArrayHelper::getValue($first,'9'))?>">
                                <h5>儿科研究所</h5>
                                <p class="iconfont icon-erke"></p>
                            </a></li>
                        <li><a href="<?=$this->generateDetailUrl(ArrayHelper::getValue($first,'7'))?>">
                                <h5>妇产科研究所</h5>
                                <p class="iconfont icon-fuchanke"></p>
                            </a></li>
                    </ul>
                </div>
                <a href="<?=$this->generateDetailUrl(ArrayHelper::getValue($first,'4'))?>" class="tumour">
                    <h5>肿瘤研究所</h5>
                    <p class="iconfont icon-zhongliu"></p>
                </a>
            </div>
            <div class="Expert-Team">
                <div class="title clearfix mb-2">
                    <a href="<?=$this->generateCategoryUrl(203)?>" class="expert pull-left">
                        <h4>Expert Team</h4>
                        <p>专家团队</p>
                    </a>
                    <a href="<?=$this->generateCategoryUrl(203)?>" class="more pull-right">More</a>
                </div>
                <div class="team">
                    <ul class="j_index-show-slider">
                        <?php
                        // 使用 $this->findFragment(栏目Id,[排序],false)->all() 获取碎片内容
                        $newsList = $this->findFragment(203,[],false)->limit(6)->all();
                        foreach ($newsList as $item): ?>
                            <li><a href="<?=$this->generateDetailUrl($item)?>">
                                    <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                                    <div class="title">
                                        <h3><?=$item->title?></h3>
                                        <p><?=$item->position?></p>
                                    </div>
                                </a></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="mouse dy-mouse">
        <img src="/images/icon.png" alt="">
    </div>
</div>
<div class="dx-content-two none">
    <div class="row">
        <div class="col-md-14 col-sm-20 col-xs-20">
            <div class="latest-papers">
                <div class="title" role="tablist">
                    <a href="#LatestPapers" class="papers actives" aria-controls="LatestPapers" role="tab" data-toggle="tab">
                        <h4>Latest Papers</h4>
                        <p>最新论文</p>
                    </a>
                    <a href="#AcademicActivities" class="active" aria-controls="AcademicActivities" role="tab" data-toggle="tab">
                        <h4>Thought Column</h4>
                        <p>思想专栏</p>
                    </a>
                    <a href="<?=$this->generateCategoryUrl(222)?>" class="more">More</a>
                </div>
                <div class="tab-content">
                    <div class="dx-tab tab-pane active" role="tabpanel" id="LatestPapers">
                        <?php
                        // 使用 $this->findFragment(栏目Id,[排序],false)->all() 获取碎片内容
                        $pushNews = $this->findFragment(222,[],false)->andWhere(['is_push'=>1])->limit(1)->all();
                        $newNews = $this->findFragment(222,[],false)->andWhere(['is_push'=>0])->limit(4)->all();
                        ?>
                        <div class="pic-text clearfix">
                            <?php foreach ($pushNews as $item):?>
                                <div class="row">
                                    <div class="col-md-7 col-xs-7 visible-lg-block visible-md-block">
                                        <a href="<?=$this->generateDetailUrl($item)?>" class="pic"><?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?></a>
                                    </div>
                                    <div class="col-md-12 col-xs-20 col-sm-20 col-md-offset-1 col-sm-offset-1 ">
                                        <div class="text">
                                            <h3><?=$item->title?></h3>
                                            <p class="time"><?=date('Y - m - d',$item->create_time)?></p>
                                            <p class="content"><?=StringHelper::truncate($item->description,120)?></p>
                                            <a href="<?=$this->generateCategoryUrl(222)?>" class="link">more</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <ul class="news-list">
                            <?php foreach ($newNews as $k=>$item):?>
                                <li><a href="<?=$this->generateDetailUrl($item)?>" class="<?=intval($k+1)==count($pushNews)?'last':'clearfix'?>"><?=$item->title?><span><?=date('Y - m - d',$item->create_time)?></span></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <div class="dx-tab tab-pane" role="tabpanel" id="AcademicActivities">
                        <?php
                        // 使用 $this->findFragment(栏目Id,[排序],false)->all() 获取碎片内容
                        $pushNews = $this->findFragment(223,[],false)->andWhere(['is_push'=>1])->limit(1)->all();
                        $newNews = $this->findFragment(223,[],false)->andWhere(['is_push'=>0])->limit(4)->all();
                        ?>
                        <div class="pic-text clearfix">
                            <?php foreach ($pushNews as $item):?>
                                <div class="row">
                                    <div class="col-md-7 col-xs-7 visible-lg-block visible-md-block">
                                        <a href="<?=$this->generateDetailUrl($item)?>" class="pic"><?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?></a>
                                    </div>
                                    <div class="col-md-12 col-xs-20 col-sm-20 col-md-offset-1 col-sm-offset-1 ">
                                        <div class="text">
                                            <h3><?=$item->title?></h3>
                                            <p class="time"><?=date('Y - m - d',$item->create_time)?></p>
                                            <p class="content"><?=StringHelper::truncate($item->description,120)?></p>
                                            <a href="<?=$this->generateCategoryUrl(222)?>" class="link">more</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <ul class="news-list">
                            <?php foreach ($newNews as $k=>$item):?>
                                <li><a href="<?=$this->generateDetailUrl($item)?>" class="<?=intval($k+1)==count($pushNews)?'last':'clearfix'?>"><?=StringHelper::truncate($item->title,15)?><span><?=date('Y - m - d',$item->create_time)?></span></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="scientific-research">
                <div class="title">
                    <a href="<?=$this->generateCategoryUrl(204)?>" class="scientific">
                        <h4>Scientific Research</h4>
                        <p>科研成果</p>
                    </a>
                    <a href="<?=$this->generateCategoryUrl(204)?>" class="more">More</a>
                </div>
                <div class="achievement">
                    <div class="row">
                        <a href="<?=$this->generateCategoryUrl(207)?>" class="col-md-3 col-xs-6 col-xs-10">
                            <div class="iconfont Research"><span class="vertical-center"></span></div>
                            <h5>科研项目</h5>
                            <p>Research Project</p>
                        </a>
                        <a href="<?=$this->generateCategoryUrl(205)?>" class="col-md-3 col-xs-6 col-xs-10">
                            <div class="iconfont Paper"><span class="vertical-center"></span></div>
                            <h5>论文</h5>
                            <p>Paper</p>
                        </a>
                        <a href="<?=$this->generateCategoryUrl(210)?>" class="col-md-3 col-xs-6 col-xs-10">
                            <div class="iconfont Work"><span class="vertical-center"></span></div>
                            <h5>著作</h5>
                            <p>Work</p>
                        </a>
                        <a href="<?=$this->generateCategoryUrl(209)?>" class="col-md-3 col-xs-6 col-xs-10">
                            <div class="iconfont Winning"><span class="vertical-center"></span></div>
                            <h5>获奖</h5>
                            <p>Winning</p>
                        </a>
                        <a href="<?=$this->generateCategoryUrl(206)?>" class="col-md-3 col-xs-6 col-xs-10">
                            <div class="iconfont Patent"><span class="vertical-center"></span></div>
                            <h5>申请首选专利</h5>
                            <p>Patent Application</p>
                        </a>
                        <a href="<?=$this->generateCategoryUrl(208)?>" class="col-md-3 col-xs-6 col-xs-10 last">
                            <div class="iconfont International"><span class="vertical-center"></span></div>
                            <h5>国际会议</h5>
                            <p>International Conference</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-20 col-xs-20 pl-6 dx-show">
            <div class="video mb-6">
                <div class="video-pic">
                    <img src="/images/temp/indexpic02.jpg" alt="">
                    <a href="javascript:;" type="button" class="start vertical-center">
                        <span class="iconfont icon-ttpodicon"></span>
                        <p>十院十年 卓越之路</p>
                    </a>
                </div>
            </div>
            <div class="friendship-link">
                <div class="title mb-3">
                    <a href="<?=$this->generateCategoryUrl(228)?>" class="friendship">
                        <h4>Friendship Link</h4>
                        <p>友情链接</p>
                    </a>
                    <a href="<?=$this->generateCategoryUrl(228)?>" class="more">More</a>
                </div>
                <div class="link">
                    <a href="<?=$this->generateCategoryUrl(228)?>">政府机构</a>
                    <a href="<?=$this->generateCategoryUrl(228)?>">兄弟单位</a>
                    <a href="<?=$this->generateCategoryUrl(228)?>">挂靠单位</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('endBody');?>
<script>
    $(function(){
        $(".j_index-slider").bxSlider({
            pager:false,
            auto:true,
        });

        $(".j_index-show-slider").bxSlider({
            pager:false
        });

        $(".active .j_tab-slider").bxSlider({
            pager:false
        });

        $('a[data-toggle="tab"]').one('shown.bs.tab', function () {
            var tarPane = $($(this).attr("href"));
            tarPane.find(".j_tab-slider").bxSlider({
                pager:false
            });
            console.log($(this));
            console.log(tarPane);
        });


        $(".dy-mouse").click(function(){
            $(".dx-content-two").removeClass("none");
            $("html,body").animate({scrollTop:1300},1000);
            $(".mouse").css("opacity","0");
            $(".footer").removeClass("none");
        });

        $(".latest-papers .title").find("a").on("click",function (e) {
            e.preventDefault();
            $(this).addClass("actives").siblings().removeClass("actives");
        });
    });
</script>
<?php $this->endBlock();?>