<?php
/**
 * Created by PhpStorm.
 * User: peilu
 * Date: 2017/7/12/0012
 * Time: 13:37
 */
require "vendor/autoload.php";
include "Book.php";
header('Content-type: text/json');

$searchContent = $_GET['searchContent'];
$page = $_GET['page'];
$method = $_GET['method'];
//echo $method;
$books = new Book($method,$searchContent,$page);
$result = [];

if ($method = 'search'){
    $sum = $books->getSum();
    $books_json = json_decode($books->getBooks(),JSON_UNESCAPED_UNICODE);
    array_push($result,array('sum'=>$sum,'books'=>$books_json));
}else{
    $book = json_decode($books->getDetail(),JSON_UNESCAPED_UNICODE);
    array_push($result,array('detail'=>$book));
}
$response = array(
    'code' => 200,
    'message'=> 'success for request',
    'data' => $result,
//    'books' => $books,
//    'sum' => $sum,
);
echo json_encode($response,JSON_UNESCAPED_UNICODE);
//echo $response;