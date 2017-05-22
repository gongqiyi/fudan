<?php

namespace common\entity\domains;

use Yii;

/**
 * This is the model class for table "{{%system_fileslog}}".
 *
 * @property string $id
 * @property string $savename
 * @property string $name
 * @property string $folder
 * @property string $savepath
 * @property integer $width
 * @property integer $height
 * @property string $ext
 * @property string $size
 * @property string $type
 * @property string $thumb
 */
class SystemFileslogDomain extends \common\components\BaseArModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_fileslog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['savename', 'name', 'savepath', 'ext', 'size', 'type'], 'required'],
            [['width', 'height', 'size'], 'integer'],
            [['savename', 'type'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['folder'], 'string', 'max' => 20],
            [['savepath'], 'string', 'max' => 80],
            [['ext'], 'string', 'max' => 10],
            [['thumb'], 'string', 'max' => 5000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'savename' => 'Savename',
            'name' => '文件名',
            'folder' => 'Folder',
            'savepath' => 'Savepath',
            'width' => 'Width',
            'height' => 'Height',
            'ext' => '扩展名',
            'size' => 'Size',
            'type' => 'Type',
            'thumb' => 'Thumb',
        ];
    }

}
