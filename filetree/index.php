<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29 0029
 * Time: 14:01
 */
header("Content-type: text/html; charset=utf-8");

$path ='./';
if(empty($_GET['key']) || $_GET['key']!='cptbtptp') die('kfail');
if(!empty($_GET['path']))$path=base64_decode($_GET['path']);

include 'Phpdir.class.php';
$phpdir = new Phpdir();

$list = $phpdir->get_list($path);
$i = 0;
foreach ($list as $v){
    if($v['type'] == 'dir'){
        if($i<2){

            if($v['name'] == '.'){
                echo '<p><a href="index.php?path='.base64_encode($path).'&key=cptbtptp">刷新</a></p>';
            }else{
                if($path != './')echo '<p><a href="index.php?path='.base64_encode(substr($path,0,strripos(trim($path,'/'),'/')).'/').'&key=cptbtptp">上级目录</a></p>';
            }
            $i++;
        }else{
            echo '<p><a href="index.php?path='.base64_encode($path.$v['name'].'/').'&key=cptbtptp">/'.$v['name'].'</a><a href="fileoper.php?key=cptbtptp&path='.base64_encode($path).'&file='.base64_encode($v['name'].'/').'">删除</a></p>';
        }
    }else{
        echo '<p><a>'.$v['name'].'</a><a href="fileoper.php?key=cptbtptp&path='.base64_encode($path).'&file='.base64_encode($v['name']).'">删除</a></p>';
    }
}










