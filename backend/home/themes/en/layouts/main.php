<?php
/**
 * @var $content
 */
use common\helpers\ArrayHelper;
use yii\helpers\Html;

// bodyClass用于设置基础布局中 <body>标签的class
$this->params['bodyClass'] = null;

$this->beginContent(Yii::$app->layoutPath.'/base.php');
?>
    <header class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-left logo outer">
                <h1 class="middle"><a class="inner" href="<?=$this->generateCategoryUrl(50)?>" ><?=\common\helpers\UrlHelper::getImgHtml($this->context->siteInfo->logo)?></a></h1>
            </div>
            <div class="navbar-toggle collapsed navbar-right" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </div>
            <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                <div class="wrap">
                    <ul class="nav navbar-nav">
                        <?php foreach ($this->context->categoryList as $item){
                            if($item['status'] != 1 || $item['pid'] != 0) continue;
                            ?>
                            <li<?=isset($this->context->parentCategoryList) && $this->context->parentCategoryList[0]['id'] == $item['id']?' class="current"':''?>><a href="<?=$this->generateCategoryUrl($item)?>"><?=$item['title']?></a></li>
                        <?php }?>
                    </ul>
                    <form class="navbar-form navbar-left" role="search" action="<?=$this->generateCategoryUrl(51)?>">
                        <div class="form-group">
                            <?php
                            // 获取筛选内容
                            $searches = Yii::$app->getRequest()->get('searches');
                            ?>
                            <?=Html::hiddenInput('searches[mid]',7)?>
                            <?=Html::textInput('searches[title]',ArrayHelper::getValue($searches,'title'),['class'=>'form-control','placeholder'=>'Keywords'])?>
                        </div>
                        <button type="submit" class="btn btn-default">Search</button>
                    </form>
                </div>
            </nav>
        </div>
    </header>
    <div id="pbody" class="pb-4">
        <?=$content?>
    </div>
    <footer>
        <div class="line"></div>
        <div class="container text-center">
            <nav>
                <?php foreach ($this->context->categoryList as $item){
                    if($item['status'] != 1 || $item['pid'] != 0) continue;
                    ?>
                    <span>|</span>
                    <a href="<?=$this->generateCategoryUrl($item)?>"><?=$item['title']?></a>
                <?php }?>
                <span>|</span><a href="<?=$this->generateCategoryUrl(1)?>">中文站首页</a>
            </nav>
            <p><?=$this->context->fragment->copyright?></p>
        </div>
    </footer>
<?php $this->endContent(); ?>