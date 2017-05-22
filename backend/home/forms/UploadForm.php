<?php
// +----------------------------------------------------------------------
// | dookay
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/6/21.
// +----------------------------------------------------------------------

/**
 * 文件上传
 */

namespace home\forms;

use common\components\BaseModel;
use Yii;
use yii\imagine\Image;

class UploadForm extends BaseModel
{
    /**
     * @var string 上传文件夹
     */
    public $folder;

    /**
     * @var mixed 图片
     */
    public $imageFile;
    /**
     * @var array 缩略图配置,[ ['with'=>300,'height'=>'300','mode'=>'outbound或inset','quality'=>50],…… ]
     */
    public $thumb;

    /**
     * @var mixed 附件
     */
    public $attachment;


    /**
     * 验证规则
     * @return array
     */
    public function rules()
    {
        return [
            [['imageFile'], 'required','on'=>['image']],
            [['imageFile'], 'image', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,gif,jpeg,bmp','maxSize'=> 1024*1024*1024,'on'=>['image']],//限制1M
            [['thumb'],function($attribute, $params){
                if ($this->hasErrors() || empty($this->$attribute)) return;

                if(!is_array($this->$attribute)){
                    $this->addError($attribute,'格式必须为一个数组。');
                }else{
                    foreach($this->$attribute as $item){
                        if(is_array($item)){
                            if(!array_key_exists('width',$item) || !array_key_exists('height',$item) || $item['width'] < 1 || $item['height'] < 1){
                                $this->addError($attribute,$this->getAttributeLabel($attribute).'格式必须包含“with”、“height”属性,且值必须大于1。');
                                break;
                            }
                            if(array_key_exists('mode',$item) && !in_array($item['mode'],['outbound','inset'])){
                                $this->addError($attribute,$this->getAttributeLabel($attribute).'生成方式只允许为“outbound”、“inset”。');
                                break;
                            }
                            if(array_key_exists('quality',$item) && ($item['quality'] < 1 || $item['quality'] > 100)){
                                $this->addError($attribute,$this->getAttributeLabel($attribute).'质量值必须是大于0小于等于100的整数');
                                break;
                            }
                        }else{
                            $this->addError($attribute,'格式必须为一个数组。');
                        }
                    }
                }
            },'on'=>['image']],
            [['attachment'], 'required','on'=>['file']],
            [['attachment'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg,jpeg,gif,bmp,png,doc,docx,xls,xlsx,ppt,pptx,pdf,txt,rar,zip,tar,7-zip,gzip','maxSize'=> 1024*1024*1024*3,'on'=>['file']],

            ['folder','default','value'=>'default'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'folder' => '文件夹',
            'imageFile' => '图片',
            'thumb' => '缩略图',
            'attachment' => '附件',
        ];
    }

    /**
     *
     * @return array|bool
     */
    public function upload()
    {
        if (!$this->validate()) return false;

        $uploadFiles = $this->scenario == 'image'?$this->imageFile:$this->attachment;
        if(!is_array($uploadFiles)) $uploadFiles = [$uploadFiles];

        $rootPath = Yii::$app->getBasePath().'/..';
        $filePath = '/uploads/'.$this->scenario.'s/'.$this->folder.'/'.date('Ym',time()).'/';

        $files = [];
        foreach($uploadFiles as $file){
            $fileInfo = [];

            $fileInfo['name'] = md5(uniqid(mt_rand(),true)); // 文件名
            $fileInfo['title'] = $file->baseName; // 原名
            $fileInfo['folder'] = $this->folder;

            $fileInfo['path'] = $filePath; // 保存路径
            $fileInfo['ext'] = $file->extension; // 扩展名
            $fileExt = '.'.$fileInfo['ext'];

            $fileInfo['file'] = $filePath.$fileInfo['name'].$fileExt;
            $fileInfo['size'] = floor($file->size/1024);

            // 保存文件
            if(!$this->createFolder($rootPath.$filePath.($this->scenario == 'image'?'thumb':''))){
                return false;
            }

            $file->saveAs($rootPath.$fileInfo['file']);

            // 生成缩略图
            if($this->scenario == 'image'){
                $imgInfo = Image::getImagine()->open($rootPath.$fileInfo['file']);
                $fileInfo['width'] = $imgInfo->getSize()->getWidth();
                $fileInfo['height'] = $imgInfo->getSize()->getHeight();

                $fileInfo['thumb'] = [];
                if(!empty($this->thumb)){
                    foreach($this->thumb as $i=>$item){
                        $fileInfo['thumb'][$i]['symbol'] = $item['width'].'x'.$item['height'];

                        $thumbPath = $filePath.'thumb/'.$fileInfo['thumb'][$i]['symbol'].'_'.$fileInfo['name'].$fileExt;
                        $fileInfo['thumb'][$i]['file'] = $thumbPath;

                        // 生成
                        $thumb = Image::thumbnail(
                            $rootPath.$fileInfo['file'],
                            $item['width'],$item['height'],
                            (array_key_exists('mode',$item)?$item['mode']:'outbound')
                        );

                        $thumb->save($rootPath.$thumbPath, [
                                'quality' => (array_key_exists('quality',$item)?$item['quality']:90)
                            ]
                        );

                        // 缩略图信息
                        $fileInfo['thumb'][$i]['width'] = $thumb->getSize()->getWidth();
                        $fileInfo['thumb'][$i]['height'] = $thumb->getSize()->getHeight();
                    }
                }
            }

            $files[] = $fileInfo;
        }

        return $files;
    }

    /**
     * 创建文件夹
     * @param $dirName
     * @return bool
     */
    private function createFolder($dirName){
        if (!file_exists($dirName) && !mkdir($dirName, 0777, true)) {
            $this->addError('folder','目录创建失败');
            return false;
        }
        else if (!is_writeable($dirName)) {
            $this->addError('folder','目录没有写权限');
            return false;
        }
        return true;
    }
}