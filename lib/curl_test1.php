<?php
/**
 * Created by PhpStorm.
 * User: peilu
 * Date: 2017/7/19/0019
 * Time: 14:15
 *///login_test

$cookie_file = "H:\Programing\Codes\WWW\learnvue\TMP.cookie";
$login_url = 'http://202.116.174.108:8080/reader/login.php';
$post_url = 'http://202.116.174.108:8080/reader/redr_verify.php';
$verify_code_url = 'http://202.116.174.108:8080/reader/captcha.php';
$info_url ='http://202.116.174.108:8080/reader/book_lst.php';

function login_post($url,$cookie,$post){
    //初始化curl模块
    $curl =  curl_init();
    //登陆提交的地址
    curl_setopt($curl,CURLOPT_URL,$url);
    //是否显示头部
    curl_setopt($curl,CURLOPT_HEADER,0);
    //是否显示返回的信息
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,0);
    //设置cookie信息保存到指定文件中
    curl_setopt($curl,CURLOPT_COOKIEFILE,$cookie);
    //post 方法
    curl_setopt($curl,CURLOPT_POST,1);
    //要提交的信息
    curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($post));
    //执行curl
    curl_exec($curl);
    //关闭，释放资源
    curl_close($curl);
//
}
function get_verify_code($url,$cookie){
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_HEADER,0);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_COOKIEFILE,$cookie);
    $img = curl_exec($curl);
    curl_close($curl);
    $fp = fopen("verify_code.jpg","w");
    fwrite($fp,$img);
    fclose($fp);
}
function get_content($url,$cookie){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36');
    curl_setopt($ch,CURLOPT_REFERER,'http://202.116.174.108:8080/reader/login.php');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
    curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie);
    $rs = curl_exec($ch);
    curl_close($ch);
    return $rs;
}
$post = array(
    'number'=>'201525010107',
    'passwd'=>'001',
    'captcha'=>$_GET['code'],
    'select'=>'cert_no'
);
login_post($post_url,$cookie_file,$post);
$result = get_content($info_url,$cookie_file);
echo $result;