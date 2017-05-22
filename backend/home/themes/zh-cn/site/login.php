<?php
/**
 * @var $model
 */
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerCss('
.input-group-addon{padding:0;}
.input-group-addon img{height:29px;border-radius: 0 4px 4px 0;}
');
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
            </ol>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'validateOnBlur'=>false,
                'validateOnSubmit'=>true,
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['value'=>'dookay'])?>

            <?= $form->field($model, 'password')->passwordInput(['value'=>'100001'])?>

            <?= $form->field($model, 'captcha')
                ->widget(Captcha::className(),[
                    'template' => '<div class="input-group">{input}<span class="input-group-addon">{image}</span></div>',
                ]); ?>

            <?= Html::submitButton('登录',['class'=>'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php $this->beginBlock('endBody');?>
    <script>
        $(function () {
            // 用户登录
            $('#login-form').on('beforeSubmit', function (e) {
                var $form = $(this);
                $.post($form.attr('action'),$form.serialize(),function (response) {
                    response = $.parseJSON(response);
                    if(response.status){
                        location.href = response.jumpLink;
                    }else{
                        alert(handleAjaxError(response.message));
                        $('#loginform-captcha-image').trigger('click');
                    }
                });
            }).on('submit', function (e) {
                e.preventDefault();
            });
        });
    </script>
<?php $this->endBlock();?>