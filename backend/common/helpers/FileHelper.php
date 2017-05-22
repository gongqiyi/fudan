<?php
// +----------------------------------------------------------------------
// | dookay
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/7/4.
// +----------------------------------------------------------------------

/**
 * 文件帮助类
 */

namespace common\helpers;


class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * 获取给定文件夹下的子文件夹名（不包括后代文件夹）
     * @param $path
     * @return array|string
     */
    static public function findChild($path)
    {
        $folderName = array();
        if (!is_dir($path)) return $folderName;

        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') continue;
            $newPath = $path . "/" . $file;
            if (is_dir($newPath)) {
                $file = iconv("GB2312", "UTF-8", $file);
                $folderName[] = $file;
            }
        }
        closedir($handle);
        return $folderName;
    }
}