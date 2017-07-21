<?php

/**
 * Created by PhpStorm.
 * User: peilu
 * Date: 2017/7/13/0013
 * Time: 15:00
 */
class Book
{
    var $html_dom;
    var $currentPage;

    function __construct($method, $searchContent = '', $page = 1)
    {
        $base_url = 'http://202.116.174.108:8080/opac/';
        if ($method == 'search') {
            $method_url = 'search_adv_result.php?sort=pubYear&desc=true&sType0=02&q0=';
            $url = $base_url . $method_url . $searchContent . "&page=" . $page;
        } else {
            $method_url = $searchContent;
            $url = $base_url . 'item.php?marc_no=' . $method_url;
        }
        //初始化curl
        $ch = curl_init($url);
        //打开文件，不必要
//        $fp = fopen("lib.html", "w");
        //写入文件
//        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //将结果以字符串返回，而不是直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $html = curl_exec($ch);
        //关闭文件和curl，释放资源
        curl_close($ch);
//        fclose($fp);
//        echo $html;
//        var_dump($html);
        $this->html_dom = new \HtmlParser\ParserDom();
        $this->html_dom->load($html);
    }

    function getSum()
    {
        // get the sum of results
        $sum = $this->html_dom->find('div.box_bgcolor font')[2]->getPlainText();
        return $sum;
    }

    function getBooks()
    {
        // get books
        $tr_array = $this->html_dom->find('tr');
        $books = array();
        foreach ($tr_array as $tr) {
            //    echo $tr->getPlainText()."<br>";
            if ($tr->find('td', 0)->getPlainText() == 0) {
                continue;
            }
            if (substr_count($tr->find('td')[2]->getPlainText(),'by')>0) {
                continue;
            }
            $book['url'] = "/learnvue/detail.php?marc_no=" . explode('=', $tr->find('td a')[0]->getAttr('href'))[1];
            $book['title'] = $tr->find('td')[1]->getPlainText();
            $book['author'] = $tr->find('td')[2]->getPlainText();
            $book['compress'] = $tr->find('td')[3]->getPlainText();
            $book['index'] = $tr->find('td')[4]->getPlainText();
            array_push($books, $book);
        }
        //JSON_UNESCAPED_UNICODE 解决中文编码问题
        $books_json = json_encode($books, JSON_UNESCAPED_UNICODE);
//        var_dump($books_json);
        return $books_json;
    }

    function getDetail()
    {
        $book = array();
        $lend_info = array();
        $book['title'] = explode('/', $this->html_dom->find('dl dd')[0]->getPlainText())[0];
        preg_match_all('/\d/S', explode('/', explode(' ', $this->html_dom->find('dl dd')[2]->getPlainText())[0])[0], $matches);
        $book['isbn'] = implode('', $matches[0]);
        if (substr_count($this->html_dom->find('dl dd')[0]->getPlainText(), 'by')) {
            $book['author'] = explode('by', $this->html_dom->find('dl dd')[0]->getPlainText())[1];
        } else {
            $book['author'] = explode('/', $this->html_dom->find('dl dd')[0]->getPlainText())[1];
        }
        $book['compress'] = explode(':', $this->html_dom->find('dl dd')[1]->getPlainText())[1];
        // $book['index'] = $this->html_dom->find('table tbody tr td')[1]->getPlainText();
        if ($this->html_dom->find('#book_img')[0]->getAttr('src') == 'http://202.116.174.108:8080/tpl/images/nobook.jpg' || $this->html_dom->find('#book_img')[0]->getAttr('src') == NULL) {
            $book['pic_link'] = 'nobook.jpg';
        } else {
            $book['pic_link'] = $this->html_dom->find('#book_img')[0]->getAttr('src');
        };
        $lend_info_array = $this->html_dom->find('tr.whitetext');
        if (substr_count($lend_info_array[0]->getPlainText(), '此书刊可能正在订购中或者处理中')) {
                $lend_info =null;
        } else {
//            var_dump($lend_info_array[0]->getPlainText());
            foreach ($lend_info_array as $info) {
                $code = $info->find('td')[1]->getPlainText();
                $location = $info->find('td')[3]->getPlainText();
                $status = $info->find('td')[4]->getPlainText();
                array_push($lend_info, array('code' => $code, 'location' => $location, 'status' => $status));
            }
            $book['lend_info'] = $lend_info;
            $book_json = json_encode($book, JSON_UNESCAPED_UNICODE);
            return $book_json;
        }
    }
}