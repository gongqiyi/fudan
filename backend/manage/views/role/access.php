<?php
/**
 * @block topButton 顶部按钮
 * @var $accessList
 * @var $nodeList
 * @var $roleId
 */

use manage\assets\ListAsset;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = '权限设置';

ListAsset::register($this);
\manage\assets\ZtreeAsset::register($this);
$this->registerJsFile('@web/js/pages/role.js',['depends' => [ListAsset::className()]]);
$this->registerJs("var accessList = ".$accessList.",nodeList = ".$nodeList.';', View::POS_END);
$this->registerJs("listApp.init();roleApp.init();roleApp.access(nodeList,accessList);", View::POS_READY);
?>
<div class="container-fluid">
<?php $form = ActiveForm::begin(['id'=>'j_form','options' => ['class'=>'form-horizontal']]); ?>
    <div class="form-group">
        <input type="hidden" name="Access[access]" id="access">
        <ul class="ztree" id="j_tree_access"></ul>
        <input type="hidden" name="Access[role_id]" value="<?=$roleId?>">
    </div>
<?php ActiveForm::end();?>
</div>
