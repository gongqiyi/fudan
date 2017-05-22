<?php

// 通过 $this->findCategoryById(栏目id) 来获取栏目数据，返回对象
use common\helpers\ArrayHelper;
use common\helpers\UrlHelper;
use yii\helpers\StringHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
$dataProvider->pagination = [
    'pageSize'=>0,
    'defaultPageSize'=>0
];
$dataList = $dataProvider->getModels();
?>
<?=$this->render('/layouts/_bread')?>
    <div class="Academy mt-4 mb-6">
        <div class="row">
            <?php if (!empty($dataList)) {?>
                <div class="j_feedback_slide">
                    <?php foreach ($dataList as $item):?>
                        <div class="col-lg-5 col-md-10 col-sm-10">
                            <p class="honor-slide none"><?=$item->title?></p>
                            <a href="javascript:;" class="pic">
                                <?=UrlHelper::getImgHtml($item->thumb,['alt'=>$item->title])?>
                                <p class="honor"><?=$item->title?></p>
                            </a>
                        </div>
                    <?php endforeach;?>
                </div>
            <?php } else {
                ?>
                <div class="text-center">
                    <h3>暂无数据</h3>
                    <p>没找到数据，去其他页面看看吧！</p>
                </div>
            <?php } ?>
        </div>
        <!--图片弹出层-->
        <div class="basic-content-w slider-model none" id="j_slider_modal">
            <div class="do-slide-w" id="j_slider_w">
                <div class="text-center mt-2">
                    <a href="#" id="j_close_layer" class="font-16"><img src="/images/close.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>
<?php $this->beginBlock('endBody');?>
    <script>
        $(function(){
            $(".j_feedback_slide").each(function () {
                $(this).find("a").on("click",function (e) {
                    e.preventDefault();
                    $('#j_slider_modal').addClass('active');
                    $('#j_slider_modal').removeClass('none');
                    //$("#j_slider_modal .bx-wrapper").find(".honor-slide").removeClass("none");
                    //点击图片时，将此图所在的列表复制一份到页面内的弹框结构中
                    var $slide = $(this).parents('.j_feedback_slide').clone().insertAfter('#j_close_layer');
                    //var $slides = $slide.children('.col-md-5');
                    var currentImg = $(this).parent('.col-lg-5').index();
                    $slide.bxSlider({
                        pager:false,
                        startSlide:currentImg
                    })
                });
                $('#j_close_layer').on('click', function (e) {
                    e.preventDefault();
                    $('#j_slider_modal').removeClass('active');
                    $('#j_slider_w').find('.bx-wrapper').remove();
                    $(".honor-slide").addClass("none");
                    $('#j_slider_modal').addClass('none');
                })
            });
        });
    </script>
<?php $this->endBlock();?>