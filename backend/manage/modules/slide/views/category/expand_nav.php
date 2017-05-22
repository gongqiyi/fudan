<?php
use yii\helpers\Url;

/**
 * tree数组输出html
 * @param $data
 * @return string
 */
function navHtml($data){
    $_html = '';
    $class='class="tree-nch" ';
    foreach($data as $key=>$value){
        $_html .= '<li '.$class. '><a '.'href="'.Url::toRoute(['/slide/slide/index','category_id'=>$value['id']]).'"'.' target="mainFrame"><span class="tree-icon"></span>'.$value['title'].'</a>';
        $_html .='</li>';
    }

    return $_html;
}
echo navHtml($dataList);
?>