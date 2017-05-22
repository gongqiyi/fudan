<?php
use common\helpers\ArrayHelper;
?>

<div class="top-tip">
    <p class="top-tip-fir"><?= ArrayHelper::getValue(ArrayHelper::getValue($this->context->parentCategoryList,0),'sub_title')?></p>
<p class="top-tip-sec"><?= ArrayHelper::getValue(ArrayHelper::getValue($this->context->parentCategoryList,0),'title')?></p>
<div class="top-tip-thr">
    您当前的位置：
    <ol class="breadcrumb" style="display: inline-block">
        <li><a href="<?= $this->generateCategoryUrl(1) ?>">首页</a></li>
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
</div>
</div>