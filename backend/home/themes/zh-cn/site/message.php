<?php
use yii\helpers\Html;

/**
 * @var $status
 * @var $message
 * @var $waitTime
 * @var $jumpLink
 */

/**
 * 信息处理
 * @param $error
 * @return string
 */
function errorHandle($error){
    $message = '';
    if(is_string($error)){
        $message = $error;
    }else{
        foreach ($error as $item){
            if(is_array($item)){
                foreach ($item as $v){
                    $message .= $v;
                }
            }else{
                $message .= $item;
            }
        }
    }
    return $message;
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-36">
            <div style="margin-top: 70px;">
                <h1><?=Html::encode($title)?></h1>
                <?php if($message){
                    echo '<pre>'.errorHandle($message).'</pre>';
                }?>
                <p>点击<a href="/" class="text-primary">返回首页</a> 或 <a href="javascript:history.go(-1);" class="text-primary">返回上一步</a>。</p>
            </div>
        </div>
    </div>
</div>
