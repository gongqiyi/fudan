<?php

namespace common\entity\models;

use common\entity\domains\SystemFileslogDomain;
use Yii;

/**
 * This is the model class for table "{{%system_fileslog}}".
 *
 */
class SystemFileslogModel extends SystemFileslogDomain
{
    /**
     * 记录文件上传日志
     * @param array $data
     * @param string $folder
     * @return $this|bool
     */
    public function create($data = [],$folder='default'){
        $this->folder = $folder?$folder:'default';
        $this->savename = $data['savename'];
        $this->name = $data['name'];
        $this->savepath = $data['savepath'];
        $this->width = $data['width'];
        $this->height = $data['height'];
        $this->ext = $data['ext'];
        $this->size = round($data['size']/1024);
        $this->type = $data['type'];
        $this->thumb = json_encode($data['thumb']);
        if($this->validate()){
            if($this->save()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
