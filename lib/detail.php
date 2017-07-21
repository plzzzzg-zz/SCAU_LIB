<?php
/**
 * Created by PhpStorm.
 * User: peilu
 * Date: 2017/7/14/0014
 * Time: 12:01
 */
require "vendor/autoload.php";
include "Book.php";
//header('Content-type: text/json');

$marc_no = $_GET['marc_no'];
$book = new Book('detail', $marc_no);
$book_json = json_decode($book->getDetail(), JSON_UNESCAPED_UNICODE);
//var_dump($book_json) ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<!--    <link rel="stylesheet" href="http://res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css"/>-->
<!--    <script type="text/javascript" src="http://res.wx.qq.com/open/libs/weuijs/1.1.2/weui.min.js"></script>-->
    <script src="vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0-alpha.6/css/bootstrap-grid.css" rel="stylesheet">
    <title>Document</title>
    <style>
        .border_bottom {
            border-bottom: 2px solid rgba(82,91,92,0.52);
        }
        li {
            list-style: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row clearfix" id="book_info" >
        <div class="col-md-4 column" style="text-align: center">
            <img style="max-height: 300px;" :src="pic_link" />
        </div>
        <div class="col-md-8 column">
            <h4 style="text-align: center">
                {{title}}
            </h4>
            <p class="text-center">
                {{author}}
            </p>
        </div>
        <div class="row clearfix border_bottom" >
            <div class="col-md-12 column  text-center">
                <ul>
                    <li>ISBN: {{isbn}}</li>
                    <li>出版社: {{compress}}</li>
                </ul>
            </div>
        </div>
        <lend_info
                v-for="info in lend_info"
                v-bind:info="info"
                v-bind:key="info.id"
        >
        </lend_info>
    </div>
</div>
</body>
</html>

<script>
    //    component 要在实例前面
    Vue.component('lend_info', {
        props: ['info'],
        //template 只识别一个外围标签，所以把东西括起来
        template: '<div class="row clearfix">' +
        '            <div class="col-md-6 column">' +
        '            馆藏地: {{info.location}}</div>' +
        '            <div class="col-md-6 column">' +
        '            状态: {{info.status}}</div>' +
        '        </div>'
    });
    book_json = <?php echo $book->getDetail();?>;
    console.log(book_json);
    // 豆瓣api不支持跨域，所以用了https://bird.ioliu.cn/ 的接口代理
    url = "https://bird.ioliu.cn/v1/?url=https://api.douban.com/v2/book/isbn/" + book_json['isbn'];
    let book = new Vue({
        el: "#book_info",
        data: book_json,
        created: function () {
            // this.getPicLink();
        },
        mounted: function () {
            axios.get(url, {
                headers: {'Content-Type': 'application/json'}
            }).then((response) => {
                console.log(response.data);
                book.pic_link = response.data.images.large;
                document.title = response.data.title;
            }).catch(function (error) {
                console.log(error);
            })
        },
        methods: {
            getPicLink: function () {
                this.$https.get(url, function (data) {
                    book.pic_link = data.data.imgae;
                });
            }
        }
    });
</script>
