<?php
/**
 * Created by PhpStorm.
 * User: peilu
 * Date: 2017/7/12/0012
 * Time: 13:19
 */
//basic
//$ch = curl_init("http://202.116.174.108:8080/opac/search_adv_result.php?sType0=02&q0=laravel");
//$fp = fopen("lib.html", "w");
//curl_setopt($ch, CURLOPT_FILE, $fp);
//curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_exec($ch);
//curl_close($ch);
//fclose($fp);

$cookie_file = "H:\Programing\Codes\WWW\learnvue\TMP.cookie";
$login_url = 'http://202.116.174.108:8080/reader/login.php';
$post_url = 'http://202.116.174.108:8080/reader/redr_verify.php';
$verify_code_url = 'http://202.116.174.108:8080/reader/captcha.php';
$info_url ='http://202.116.174.108:8080/reader/book_lst.php';
//login_test

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
    curl_setopt($ch,CURLOPT_TIMEOUT,120);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie);
    $rs = curl_exec($ch);
    curl_close($ch);
    return $rs;
}

get_content($login_url,$cookie_file);
get_verify_code($verify_code_url,$cookie_file);
echo "<img src='verify_code.jpg'>";
?>
<form action="curl_test1.php" method="get">
    <input type="text" name="code">
    <input type="submit">
</form>
