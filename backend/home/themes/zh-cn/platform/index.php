<?php

$parentCategory = $this->findCategoryById($this->context->categoryInfo->pid);
?>
<?=$this->render('/layouts/_bread')?>
<div class="medical-wrapper">
    <div class="platform-con clearfix">
        <div class="pt-2">
            <?php
            // 使用 $dataProvider->getModels() 查询获取数据列表
            $dataProvider->pagination = [
                'pageSize'=>0,
                'defaultPageSize'=>0
            ];
            $dataList = $dataProvider->getModels();
            if (!empty($dataList)) {
                ?>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php foreach ($dataList as $k=>$item) { ?>
                        <div class="panel">
                            <div class="panel-heading clearfix" role="tab" id="heading<?=$k?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$k?>" aria-expanded="<?=$k?'false':'true'?>" aria-controls="collapse<?=$k?>">
                                <h4 class="panel-title">
                                    <a role="button">
                                        <?=$item->title?>
                                    </a>
                                </h4>
                                <i class="iconfont icon-jiantou-copy pull-right <?=$k?'':'xuanzhuan'?>"></i>
                            </div>
                            <div id="collapse<?=$k?>" class="panel-collapse collapse <?=$k?'':'in'?>" role="tabpanel" aria-labelledby="heading<?=$k?>">
                                <?=$item->content?>
                            </div>
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
    </div>
</div>
<?php $this->beginBlock('endBody');?>
<script>
    $(function(){
        $(".panel-heading").click(function(){
            if($(this).parent().find(".panel-collapse").has("in")){
                $(this).find("i").addClass("xuanzhuan");
                $(this).parent().siblings().find("i").removeClass("xuanzhuan");
            }
            if(!$(this).hasClass("collapsed")) {
                $(this).find("i").removeClass("xuanzhuan");
            }
        });
    })
</script>
<?php $this->endBlock();?>
