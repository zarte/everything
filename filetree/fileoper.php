<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/29 0029
 * Time: 14:31
 */

if(!empty($_GET['key']) && !empty($_GET['file']) && !empty($_GET['path'])){
    if($_GET['key'] != 'cptbtptp'){
        echo 'kfalse';
        exit();
    }
    $file = base64_decode($_GET['file']);
    $path = base64_decode($_GET['path']);
    include 'Phpdir.class.php';
    $phpdir  = new Phpdir();
    if($phpdir->set_path($path)){
        if($phpdir->delfile($file)){
            header("Location: ".$_SERVER['HTTP_REFERER']);
        }else{
            echo 1;exit();
        }
    }else{
        echo 3;exit();
    }

}