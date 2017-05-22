<?php
// +----------------------------------------------------------------------
// | nantong
// +----------------------------------------------------------------------
// | Copyright (c) 2015-+ http://www.dookay.com/.
// +----------------------------------------------------------------------
// | Author: xiaopig <xiaopig123456@qq.com>
// +----------------------------------------------------------------------
// | Created on 2016/4/11.
// +----------------------------------------------------------------------

/**
 * url生成
 */

namespace common\helpers;

use yii\helpers\Html;
use yii\imagine\Image;

class UrlHelper extends \yii\helpers\Url
{
    /**
     * 生成node列表url
     * @param $item object|array 栏目
     * @param $siteList array 站点列表
     * @param array $params
     * $params['module'] string 模块名，示例：'prototype'或'html5/effect/……'。
     * $params['scheme'] boolean|string 是否包含域名
     * $params['params']=>[] url参数
     * @return string
     */
    public static function categoryPage($item,$siteList,$params = []){
        if(is_int($item) && array_key_exists('categoryList',$params)) $item = $params['categoryList'][$item];
        if(is_object($item)) $item = ArrayHelper::toArray($item);

        // 优先使用固定url
        if(!empty($item['link'])) return $item['link'];

        $params = self::getDefaultParams($params);

        $siteInfo = $siteList[$item['site_id']];
        if(!$siteInfo['is_default']) $params = ArrayHelper::merge($params,['params'=>['s'=>$siteInfo['slug']]]);

        // 首页
        if($item['slug_rules'] == 'site/index'){
            $url = self::toRoute(ArrayHelper::merge(['/site/index'],$params['params']),$params['scheme']);
        }
        // 静态url
        elseif($params['static'] && !empty($item['slug'])){
            $slugs = ArrayHelper::merge(self::convertSlugs($item['slug']),$params['params']);

            switch($item['type']){
                case 0:
                case 1:
                    $url = self::toRoute(ArrayHelper::merge([$params['moduleSymbol'].'node/index'],$slugs),$params['scheme']);
                    break;
                case 2:
                    $slugRules = self::convertSlugRules($item['slug_rules']);
                    $url = self::toRoute(ArrayHelper::merge([$params['moduleSymbol'].$slugRules['route']],$slugs),$params['scheme']);
                    break;
                case 3:
                    $url = $item['link'];
                    break;
            }
        }
        // 动态url
        else{
            switch($item['type']){
                case 0:
                case 1:
                    $url = self::toRoute(ArrayHelper::merge([$params['moduleSymbol'].'node/index','category_id'=>$item['id']],$params['params']),$params['scheme']);
                    break;
                case 2:
                    $slugRules = self::convertSlugRules($item['slug_rules']);
                    $url = self::toRoute(ArrayHelper::merge([$params['moduleSymbol'].$slugRules['route']],ArrayHelper::merge($slugRules['params'],$params['params'])),$params['scheme']);
                    break;
                case 3:
                    $url = $item['link'];
                    break;
            }
        }
        return $url;
    }

    /**
     * 生成node内容url
     * @param object|array $item 内容节点
     * @param $siteList array 站点列表
     * @param null|array $categoryList 栏目列表
     * @param array $params $params['module'] string 模块名，示例：'prototype'或'html5/effect/……'。
     * $params['scheme'] boolean|string 是否包含域名,
     * $params['params'] array 其他参数
     * $params['extraFields'] array 用于生成“自由类型栏目”详细页，例如：$params['extraFields'] = ['category_id'=>（自由类型栏目id，必须写）,……]
     * @return string
     */
    public static function detailPage($item,$siteList,$categoryList = null,$params = []){
        if(!$item) return '';
        if(is_object($item)) $item = ArrayHelper::toArray($item);

        $params = self::getDefaultParams($params);
        if(array_key_exists('extraFields',$params)) $item = ArrayHelper::merge($item,$params['extraFields']);

        $siteInfo = $siteList[$item['site_id']];
        if(!$siteInfo['is_default']) $params = ArrayHelper::merge($params,['params'=>['s'=>$siteInfo['slug']]]);

        // 伪静态url
        if($params['static'] && !empty($categoryList[$item['category_id']]['slug'])){
            $route = 'node/detail';
            if($categoryList[$item['category_id']]['type'] == 2){
                $slugRules = self::convertSlugRules($categoryList[$item['category_id']]['slug_rules_detail']);
                $route = $slugRules['route'];
            }
            $url = self::toRoute(ArrayHelper::merge([$params['moduleSymbol'].$route,'id'=>$item['id']],self::convertSlugs($categoryList[$item['category_id']]['slug']),$params['params']),$params['scheme']);
        }
        // 动态url
        else{
            if($categoryList[$item['category_id']]['type'] == 2){
                $slugRules = self::convertSlugRules($item['slug_rules_detail']);
                $url = self::toRoute(ArrayHelper::merge([$params['moduleSymbol'].$slugRules['route'],'category_id'=>$item['category_id'],'id'=>$item['id']],$slugRules['params'],$params['params']),$params['scheme']);
            }else{
                $url = self::toRoute(ArrayHelper::merge([$params['moduleSymbol'].'node/detail','category_id'=>$item['category_id'],'id'=>$item['id']],$params['params']),$params['scheme']);
            }
        }

        return $url;
    }

    /**
     * 生成node表单类型模型 请求url
     * @param $modelId
     * @param array $params
     * @return string
     */
    static public function formRequest($modelId,$params=[]){
        $params = self::getDefaultParams($params);
        return self::toRoute([$params['moduleSymbol'].'form/index','model_id'=>$modelId],$params['scheme']);
    }

    /**
     * 生成附件下载链接
     * @param $item
     * @param array $params
     * @return string
     */
    static public function download($item,$params=[]){
        $params = self::getDefaultParams($params);
        return self::toRoute(ArrayHelper::merge([$params['moduleSymbol'].'node/download','category_id'=>$item->category_id],$params['params']),$params['scheme']);
    }

    /**
     * 获取默认参数配置
     * @param $params
     * @return array
     */
    static private function getDefaultParams($params){
        $params = ArrayHelper::merge([
            'static'=> true, // 是否开启伪静态
            'scheme'=>false, // 生成链接是否带域名
            'inModule'=>false, //是否在模块内
            'params'=>[],// url其他参数
        ],$params);

        $params['moduleSymbol'] = $params['inModule']?'/':'';

        return $params;
    }

    /**
     * slug 转换
     * @param $slug
     * @return array
     */
    static public function convertSlugs($slug){
        $slugs = [];
        foreach(explode('/',$slug) as $i=>$item){
            $slugs['slug_'.$i] = $item;
        }
        return $slugs;
    }

    /**
     * 自由类型的 slugRules 字段解析
     * @param $slugRules
     * @return array
     */
    static public function convertSlugRules($slugRules){
        $slugRules = explode('?',$slugRules);
        $params = [];
        if(array_key_exists(1,$slugRules)) {
            foreach (explode('&', $slugRules[1]) as $item) {
                $temp = explode('=',$item);
                if(count($temp) == 2){
                    $params[$temp[0]] = $temp[1];
                }
            }
        }

        return [
            'route'=>$slugRules[0],
            'params'=>$params
        ];
    }

    /**
     * 获取文件项目信息
     * @param string|array $fileData 如果多图片请用fileDataHandle()方法处理,单图片直接传入
     * @param string $item 获取项
     * @param string $thumbSize 获取项为thumb|thumbWidth|thumbHeight 时下有效
     * @return string
     */
    public static function getFileItem($fileData,$item = 'file',$thumbSize = ''){
        if(!is_array($fileData)){
            if(is_string($fileData) && strpos($fileData,'[{') === 0){
                $fileData = self::fileDataHandle($fileData, false);
            }else{
                return $fileData?:'';
            }
        }

        $result = '';

        $isThumb = false;
        if(in_array($item,['thumb','thumbWidth','thumbHeight'])){
            $isThumb = $item;
            $item = $item.'_'.str_replace('*','x',$thumbSize);
        }

        if(array_key_exists($item,$fileData)){
            $result = $fileData[$item];
        }
        // 找缩略图但是指定尺寸不存在
        elseif($isThumb){
            foreach($fileData as $k=>$v){
                if(strchr($k,'thumb_')){
                    $tmp = explode('_',$k);
                    $result = $fileData[ $isThumb.'_'.$tmp[1]];
                    break;
                }
            }

            if(empty($result)){
                $result = $isThumb == 'thumb'?$fileData['file']:0;
            }
        }

        return $result;
    }

    /**
     * 上传的文件数据处理
     * @param string $fileData 原始文件数据
     * @param bool|false $multiple 是否单文件
     * @return array
     */
    public static function fileDataHandle($fileData, $multiple = true){
        if(strpos($fileData,'[{') === 0){
            $json_image_data = json_decode($fileData,true);
            if($json_image_data == null)
                return $fileData;
            $resultImg = [];
            foreach($json_image_data as $i=>$item){
                if(!$multiple){
                    $resultImg = self::fileDataItem($item);
                    break;
                }else{
                    $resultImg[] = self::fileDataItem($item);
                }
            }
            return $resultImg;
        }

        return [];
    }

    private static function fileDataItem($fileItem){
        $result = [];

        $webImg = stripos($fileItem['file'],'http://',0)===0 || stripos($fileItem['file'],'https://',0) === 0;

        foreach((array)$fileItem as $i=>$item){
            switch($i){
                case 'file':
                    $result[$i] = $webImg?:$item;
                    break;
                case 'thumb':
                    foreach($item as $k=>$v){
                        $result['thumb_'.$v['symbol']] = $v['file'];
                        $result['thumbWidth_'.$v['symbol']] = $v['width'];
                        $result['thumbHeight_'.$v['symbol']] = $v['height'];
                    }
                    break;
                default:
                    $result[$i] = $item;
                    break;
            }
        }
        return $result;
    }

    /**
     * 生成缩略图
     * @param $fileData
     * @param array $options 索引0的值为缩略图配置"w{宽}/300/h{高}/300/q{质量0~100}/80/m{1居中裁剪|2缩放裁剪}/1"，其他为html::img()方法的options
     * @return string
     */
    public static function getImgHtml($fileData,$options=[]){
        if(!is_array($fileData)){
            $tmp = self::fileDataHandle($fileData,false);
            if(!empty($tmp)) $fileData = $tmp;
        }
        $src = self::getFileItem($fileData);

        if(!$src) return '';

        $options = ArrayHelper::merge(['alt'=>self::getFileItem($fileData,'alt')],$options);
        if(!array_key_exists(0,$options) || stripos('http://',$src,0) || stripos('https://',$src,0)){
            if(array_key_exists(0,$options)) unset($options[0]);
            return Html::img($src,$options);
        }

        // 格式化缩略图配置信息
        $thumbInfoKey = $thumbInfoValue = [];
        foreach (explode('/',$options[0]) as $i=>$item){
            ($i+1)%2 !== 0?$thumbInfoKey[] = $item:$thumbInfoValue[] = $item;
        }
        $thumbInfo = [];
        foreach ($thumbInfoKey as $i=>$item){
            $thumbInfo[$item] = ArrayHelper::getValue($thumbInfoValue,$i);
        }
        unset($thumbInfoKey,$thumbInfoValue,$options[0]);

        if(!array_key_exists('w',$thumbInfo) || !array_key_exists('h',$thumbInfo)) return Html::img($src,$options);
        $thumbInfo['m'] = ArrayHelper::getValue([1=>'outbound',2=>'inset'],ArrayHelper::getValue($thumbInfo,'m',1),'outbound');
        $thumbInfo['q'] = abs(ArrayHelper::getValue($thumbInfo,'q',80));
        if($thumbInfo['q'] > 100 || $thumbInfo['q'] === 0) $thumbInfo['q'] = 100;

        // 生成缩略图资源地址
        $fileName = substr($src,strrpos($src,'/')+1);
        $start = strpos($fileName,'.');
        $thumbSrc = str_replace($fileName,substr_replace($fileName,'_'.$thumbInfo['w'].'x'.$thumbInfo['h'].'x'.$thumbInfo['q'].'.',$start,1),$src);
        unset($fileName,$start);

        // 生成缩略图
        $base = \Yii::$app->basePath.'/..';
        if(!file_exists($base.$thumbSrc)){
            if(file_exists($base.$src)){
                $thumb = Image::thumbnail($base.$src, $thumbInfo['w'],$thumbInfo['h'],$thumbInfo['m']);
                $thumb->save($base.$thumbSrc, [
                        'quality' => $thumbInfo['q']
                    ]
                );
            }else{
                $thumbSrc = $src;
            }
        }

        return Html::img($thumbSrc,$options);
    }
}