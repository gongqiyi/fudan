<?php
/**
 * @var $content
 *
 * @params $titleClass
 * @params $subTitle
 *
 * @blocks $topButton
 */

$this->beginContent(Yii::$app->layoutPath.'/base.php'); ?>
    <div class="container-fluid">
        <div class="page-header">
            <h1<?=isset($this->params['titleClass'])?' class="'.$this->params['titleClass'].'"':''?>><?=$this->title?><?=isset($this->params['subTitle'])?' <small>'.$this->params['subTitle'].'</small>':''?></h1>
            <div class="fun">
                <?php if (isset($this->blocks['topButton'])): ?>
                    <?= $this->blocks['topButton'] ?>
                <?php endif; ?>
            </div>
        </div>
        <?= $content ?>
        <!-- 底部开始 -->
        <footer>
            <nav>
                <a href="http://www.dookay.com/" target="_blank">稻壳官网</a>
            </nav>
            ©<a href="http://www.dookay.com/" target="_blank">www.dookay.com</a> 版权所有
        </footer><!-- 底部结束-->
    </div>
<?php $this->endContent(); ?>