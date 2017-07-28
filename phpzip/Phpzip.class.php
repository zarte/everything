<?php

/****
 * Class Phpzip
 * zip解压类
 * author aloner
 * 17.6.29
 *
 */

class Phpzip {

    private $path='./';                                     //目标路径
    private $zipfilename='';                                //源文件完整路径
    private $return_info = array('err_code'=>'00001','msg'=>'源文件未添加'); //返回信息
    private $maxsize = 1024 * 1024 * 6;                     //单个文件大小限制，超过不解压。(单位b)
    private $is_windows = true;                             //windows下开启转码
    private $zp='';                                         //zip对像


    /**
     * @param $path 目标路径
     */
    public function set_path($path){
        $this->path = $path;
    }

    /**
     * @param $filename 完整路径
     */
    public function set_file($filename){
        $this->zipfilename = $filename;
    }

    /**
     *  开始解压
     * @return array err_code,msg
     */
    public function get_unfile(){
        if(empty($this->zipfilename) || !is_file($this->zipfilename)) return $this->return_info;
        if($this->unzipfile()){
            return $this->return_info;
        }else{
            return $this->return_info;
        }
    }

    private function unzipfile(){
        $this->zp = zip_open($this->zipfilename);
        if($this->zp) {
            $starttime = explode(' ',microtime()); //解压开始的时间
            $i = 0;
            while ($dir_resource = zip_read($this->zp)) {
                //如果能打开则继续
                if (zip_entry_open($this->zp, $dir_resource)) {
                    //获取当前项目的名称,即压缩包里面当前对应的文件名
                    $file_name = $this->path.zip_entry_name($dir_resource);
                    //以最后一个“/”分割,再用字符串截取出路径部分
                    $file_path = substr($file_name, 0, strrpos($file_name, "/"));
                    //如果路径不存在，则创建一个目录，true表示可以创建多级目录
                    if (!is_dir($file_path)) {
                        mkdir($file_path, 0777, true);
                    }
                    //如果不是目录，则写入文件
                    if (!is_dir($file_name)) {
                        //读取这个文件
                        $file_size = zip_entry_filesize($dir_resource);
                       //如果文件过大，跳过解压，继续下一个
                        if ($file_size < ($this->maxsize)) {
                            $file_content = zip_entry_read($dir_resource, $file_size);
                            //windows下中文转码
                            $this->is_windows?$file_name = mb_convert_encoding($file_name,'GBK','auto'):'';
                            file_put_contents($file_name, $file_content);
                        } else {
                            $i++;
                        }
                    }
                }
                zip_entry_close($dir_resource);
            }

        }else{
            $this->return_info['err_code'] = '00002';
            $this->return_info['msg'] = '打开压缩文件失败';
            return false;
        }
        $this->return_info['msg'] = '';
        $this->return_info['err_code'] = null;
        if($i)$this->return_info['msg'] .=$i.'个文件被跳过';
        zip_close($this->zp);
        $endtime = explode(' ',microtime()); //解压结束的时间
        $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
        $thistime = round($thistime,3); //保留3为小数
        $this->return_info['msg'] .= ' 解压完毕!花费'.$thistime.'秒';
        return true;
    }



}

