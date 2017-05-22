<?php
/**
 * 视图类提供以下属性和方法供使用
 * $this->findModel($modelId,$nodeId=null) 实例化一个node模型,$nodeId可选如果不为空则会查找当前模型下的指定数据详情
 * $this->findFragment($categoryId|$modelName,$sort = [],$query = true) 获取碎片列表
 * $this->findFragmentPage($categoryId) 获取单网页碎片
 * $this->findCategoryById($categoryId) 根据栏目id查找对应栏目信息
 * $this->findAdList($adCategoryId) 获取广告列表
 *
 * $this->generateCategoryUrl($item|$categoryId,$params = []) 生成栏目url
 * $this->generateDetailUrl($item,$params = []) 生成内容详情url
 * $this->generateFormUrl($modelId) 生成前台表单url（表单模型）
 *
 *
 * 全局公用属性
 * $this->context->isMobile bool 当前访问是否移动设备
 * $this->context->siteList array 站点列表
 * $this->context->siteInfo obj 当前站点信息
 * $this->context->fragment obj 碎片信息
 * $this->context->allCategoryList array 所有站点栏目列表
 * $this->context->categoryList array 本站栏目列表
 * $this->context->categoryInfo object 当前页栏目信息
 * $this->context->subCategoryList array 当前页栏目子栏目列表
 * $this->context->parentCategoryList array 当前页栏目父栏目列表
 * $this->context->config object 网站的配置信息
 *
 *
 * 其他
 * Yii::$app->getRequest()->get(变量名,默认值[可选]) 获取get请求的参数
 * Yii::$app->getRequest()->post(变量名,默认值[可选]) 获取post请求的参数
 * array_key_exists(键,$array) 判断数组键是否存在
 * array_chunk($array,长度) 数组按照长度分组
 * dump() 打印数据
 *
 * @var $content
 */

use common\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use home\assets\CommonAsset;
use home\assets\Ie9Asset;

$this->registerMetaTag(['name'=>'keywords','content'=>Html::encode($this->keywords)]);
$this->registerMetaTag(['name'=>'description','content'=>Html::encode($this->description)]);
$this->registerLinkTag(['rel' => 'shortcut icon','href'=>'favicon.ico']);
$this->registerLinkTag(['rel' => 'bookmark','href'=>'favicon.ico']);

$this->registerJs("
// csrf
var csrfParam = {name:\"".Yii::$app->getRequest()->csrfParam."\",value:\"".Yii::$app->getRequest()->getCsrfToken()."\"};

// ajax提交表单返回错误信息处理
function handleAjaxError(message){
    var _message = '';
    if(typeof message == 'string'){
        _message = message;
    }else{
        for (var i in message){
            for (var f=0;f<=message[i].length;f++){
                if(typeof message[i][f] != 'undefined') _message +=message[i][f];
            }
        }
    }
    return _message;
}
", View::POS_HEAD);

// 重置jquery资源依赖
$this->assetManager->assetMap['jquery.js'] = '@web/js/dookayui.min.js';

// 引入公共资源
CommonAsset::register($this);
Ie9Asset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="renderer" content="webkit">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title)?></title>
    <?php $this->head() ?>

</head>
<body<?=array_key_exists('bodyClass',$this->params) && !empty($this->params['bodyClass'])?' class="'.$this->params['bodyClass'].'"':''?>>
<?php $this->beginBody() ?>

<?=$content?>


<!--弹框-->
<div class="dx-alert clearfix none j_detail"></div>
<?php
$this->endBody();
// 定义endBody内容块
if (isset($this->blocks['endBody'])) echo $this->blocks['endBody'];
?>
<script>
    $(function(){
        commonApp.init();
    });
    window._bd_share_config={
        "common":{
            "bdSnsKey":{},
            "bdText":"",
            "bdMini":"2",
            "bdMiniList":false,
            "bdPic":"",
            "bdStyle":"0",
            "bdSize":"32"
        },"share":{}
    };
    with(document)0[
        (getElementsByTagName('head')[0]||body).appendChild(
            createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
</script>
</body>
</html>
<?php $this->endPage() ?>
