<?php

/**
 * @var $config
 */

$watermark_position = 0;
foreach($config as $item){
    if($item->name == 'watermark_position'){
        $watermark_position = $item->value;
        break;
    }
}
?>
<?php $this->beginBlock('watermark_position'); ?>
    <div class="form-group">
        <label class="col-sm-4 control-label">水印所在位置</label>
        <div class="col-sm-17">
            <table class="table table-bordered table-condensed">
                <tbody>
                <tr>
                    <td rowspan="3">
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="0" <?php if($watermark_position == '0') echo 'checked'; ?>> 随机位置
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="1" <?php if($watermark_position == '1') echo 'checked'; ?>> 顶部居左
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="2" <?php if($watermark_position == '2') echo 'checked'; ?>> 顶部居中
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="3" <?php if($watermark_position == '3') echo 'checked'; ?>> 顶部居右
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="4" <?php if($watermark_position == '4') echo 'checked'; ?>> 中部居左
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="5" <?php if($watermark_position == '5') echo 'checked'; ?>> 中部居中
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="6" <?php if($watermark_position == '6') echo 'checked'; ?>> 中部居右
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="7" <?php if($watermark_position == '7') echo 'checked'; ?>> 底部居左
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="8" <?php if($watermark_position == '8') echo 'checked'; ?>> 底部居中
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="checkbox">
                            <label>
                                <input type="radio" name="Config[watermark_position]" value="9" <?php if($watermark_position == '9') echo 'checked'; ?>> 底部居右
                            </label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.getElementById('systemconfigmodel-6-value').setAttribute('readonly','readonly');
    </script>
<?php $this->endBlock(); ?>