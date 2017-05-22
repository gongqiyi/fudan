<?php

/**
 * @var $model
 * @userInfo
 */

use manage\assets\LoginAsset;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = '登录 - '.$this->context->config['site']['site_name'].'系统管理中心';

LoginAsset::register($this);
$this->registerJs("loginApp.init();", View::POS_READY);
?>
<!-- logo -->
<h1 class="brand">
    <a href="/"><img src="images/logo-login.png" draggable="false"></a>
</h1>
<!-- 登陆框开始 -->
<div class="login-box">
    <h2>用户登录</h2>
    <?php if(isset($userInfo)){?>
        <div class="form-group text-center">你好，<?=$userInfo['username']?> ！</div>
        <a class="btn btn-primary btn-block" id="j_enter_system" href="<?=\yii\helpers\Url::to(['site/index'])?>">进入系统</a>
    <?php }else{ ?>
    <?php $form = ActiveForm::begin([
        'id'=>'j_form',
        'fieldConfig'=>['template'=>'{input}{error}{hint}']
    ]); ?>
    <?= $form->field($model, 'username')->textInput(['placeholder'=>$model->getAttributeLabel('username')]) ?>
    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>$model->getAttributeLabel('password')]) ?>
    <?= $form->field($model, 'verifyCode', ['options' => ['class' => 'form-group verificate-code clearfix']])
        ->widget(Captcha::className(),[
        'template' => '{input}<a href="javascript:;" id="j_verify_code" data-placement="bottom" data-placeholder="'.$model->getAttributeLabel('verifyCode').'" title="点击切换验证码">{image}</a>',
    ]); ?>
    <?= Html::submitButton('登&nbsp;&nbsp;录', ['class' => 'btn btn-primary', 'data-loading-text' => '登录中，请稍后...']) ?>

    <?php ActiveForm::end();?>
    <div class="find-password">
        <h4>忘记了你的密码？</h4>
        <p>不用担心，马上联系 <a href="mailto:<?= $this->context->config['site']['admin_email']?>" class="text-primary j_tooltip" data-placement="top" title="<?= $this->context->config['site']['admin_email']?>">网站管理员</a> 重置你的密码。</p>
    </div>
    <?php }?>
</div><!-- 登陆框结束 -->
<p class="copyright">© <a href="http://www.dookay.com" target="_blank">www.dookay.com</a> 版权所有</p>