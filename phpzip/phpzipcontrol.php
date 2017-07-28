<?php


if(empty($_POST['key']) || $_POST['key'] != 'cptbtptp')die('错误1');


if(empty($_FILES['program']))die('请上传文件');
if($_FILES['program']['size']>2000000)die('文件不能超2M');
include "Phpzip.class.php";
$phpzip = new Phpzip();

if(!empty($_POST['path'])){
$phpzip->set_path($_POST['path']);
}
$phpzip->set_file($_FILES['program']['tmp_name']);
$res = $phpzip->get_unfile();
if(!$res['err_code']){
    echo '{"code":200,"msg":"'.$res['msg'].'"}';
}else{
    echo '{"code":4,"msg":"'.'错误码:',$res['err_code'],' 信息:',$res['msg'].'"}';
}