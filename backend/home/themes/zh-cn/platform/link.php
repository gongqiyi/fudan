<?php

use common\helpers\ArrayHelper;

$parentCategory = $this->findCategoryById($this->context->categoryInfo->id);
$childCategory = ArrayHelper::map(ArrayHelper::getChildes($this->context->categoryList,$parentCategory->id),'id','title');
?>
<?=$this->render('/layouts/_bread')?>
<div class="medical-wrapper link-wrapper">
    <?php
    // 使用 $dataProvider->getModels() 查询获取数据列表
    $dataProvider->pagination = [
        'pageSize'=>0,
        'defaultPageSize'=>0
    ];
    $dataProvider->query->orderBy(['id'=>SORT_ASC])->asArray();
    $dataList = $dataProvider->getModels();
    $data = [];
    if (!empty($dataList)) {
        foreach ($dataList as $datas){
            foreach ($childCategory as $i=>$child){
                if($datas['category_id'] == $i){
                    $data[$i][] = $datas;
                }
            }
        }
    ?>
        <?php foreach ($data as $k=>$items):?>
            <div class="link-list">
                <h2><?=ArrayHelper::getValue($childCategory,$k)?>：</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                        <?php $_data = array_chunk($items,5); foreach ($_data as $group):?>
                            <tr>
                                <?php foreach ($group as $item):?>
                                    <td><a href="<?=$item['link']?>"><?=$item['title']?></a></td>
                                <?php endforeach;?>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach;?>
    <?php } else {
        ?>
        <div class="text-center">
            <h3>暂无数据</h3>
            <p>没找到数据，去其他页面看看吧！</p>
        </div>
    <?php } ?>
</div>
