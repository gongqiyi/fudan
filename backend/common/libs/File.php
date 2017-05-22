<?php
namespace common\libs;
// 本地文件写入存储类
class File{

    private $contents = array();
    private $errorInfo = array();

    /**
     * 架构函数
     * @access public
     */
    public function __construct() {
    }

    /**
     * 文件内容读取
     * @access public
     * @param string $filename 文件名
     * @param string $type
     * @return string
     */
    public function read($filename, $type = ''){
        if($type == 'web'){
            return $this->getWeb($filename);
        }else{
            return $this->get($filename,'content');
        }
    }

    /**
     * 文件写入
     * @access public
     * @param string $filename  文件名
     * @param string $content  文件内容
     * @return boolean         
     */
    public function put($filename,$content){
        $dir         =  dirname($filename);
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        if(false === file_put_contents($filename,$content)){
            $this->errorInfo[] = '文件写入错误：'.$filename;
            return false;
        }else{
            $this->contents[$filename]=$content;
            return true;
        }
    }

    /**
     * 文件追加写入
     * @access public
     * @param string $filename  文件名
     * @param string $content  追加的文件内容
     * @return boolean        
     */
    public function append($filename,$content){
        if(is_file($filename)){
            $content =  $this->read($filename).$content;
        }
        return $this->put($filename,$content);
    }

    /**
     * 文件是否存在
     * @access public
     * @param string $filename  文件名
     * @return boolean     
     */
    public function has($filename){
        return is_file($filename);
    }

    /**
     * 文件删除
     * @access public
     * @param string $filename  文件名
     * @return boolean     
     */
    public function unlink($filename){
        unset($this->contents[$filename]);
        return is_file($filename) ? unlink($filename) : false; 
    }

    /**
     * 读取文件信息
     * @access public
     * @param string $filename  文件名
     * @param string $name  信息名 mtime或者content
     * @return boolean     
     */
    public function get($filename,$name){
        if(!isset($this->contents[$filename])){
            if(!is_file($filename)) return false;
           $this->contents[$filename]=file_get_contents($filename);
        }
        $content=$this->contents[$filename];
        $info   =   array(
            'mtime'     =>  filemtime($filename),
            'content'   =>  $content
        );
        return $info[$name];
    }

    /**
     * 读取网页文件
     * @param $url
     * @return string
     */
    public function getWeb($url){
        $handle = fopen($url, "rb");
        $contents = stream_get_contents($handle);
        fclose($handle);
        return $contents;
    }

    /**
     * 返回错误信息
     * @return array
     */
    public function getError(){
        return $this->errorInfo;
    }
}
