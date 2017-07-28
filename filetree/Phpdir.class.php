<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29 0029
 * Time: 14:28
 */


class Phpdir{

    private $is_windows = true;
    private $cur_path = './';

    public function get_list($path){
        if(empty($path)) return false;
        $this->cur_path = $path;
        return $this->search_cur();
    }


    private function search_cur(){
        $dir_arr = array();
        $file_arr = array();
        $this->is_windows?$this->cur_path = iconv('UTF-8','GB2312',  $this->cur_path):'';
        if (is_dir($this->cur_path)) {
            if ($dh = opendir($this->cur_path)) {
                while (($file = readdir($dh)) !== false) {
                    if(filetype($this->cur_path . $file) == 'dir'){
                        $this->is_windows?$file = iconv('GB2312','UTF-8',  $file):'';
                        $dir_arr[]=array('type'=>'dir','name'=>$file);
                    }else{
                        $this->is_windows?$file = iconv('GB2312','UTF-8',  $file):'';
                        $file_arr[]=array('type'=>'file','name'=>$file);
                    }
                }
                closedir($dh);
            }
        }
        return array_merge($dir_arr,$file_arr);
    }

    public function delfile($file){
        $this->is_windows?$file = iconv('UTF-8','GB2312',  $file):'';
        $file = $this->cur_path.$file;
        if($this->delall($file)) return true;
    }

    private function delall($path){
        if(is_file($path)){
            unlink($path);
            return true;
        }else if(is_dir($path)){
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if($file == '.' || $file == '..') continue;
                    if(filetype($path . $file) == 'dir') {
                        $this->delall($path.$file.'/');
                    }else {
                        unlink($path.$file);
                    }
                }
                closedir($dh);
            }
            rmdir($path);
            return true;
        }else{
            echo '文件不存在';
            return false;
        }

    }
    public function set_path($path){
        $this->is_windows?$path = iconv('UTF-8','GB2312',  $path):'';
        if(is_dir($path)){
            $this->cur_path = $path;
            return true;
        }else{
            return false;
        }
    }
}