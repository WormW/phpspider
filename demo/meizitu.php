<?php
ini_set("memory_limit", "1024M");
require dirname(__FILE__) . '/../core/init.php';

/* Do NOT delete this comment */
/* 不要删除这段注释 */


$configs = array(
    'name' => 'meizitu',
    'tasknum' => 8,
    'domains' => array(
        'meizitu.com',
        'www.meizitu.com'
    ),
    'scan_urls' => array(
        'http://www.meizitu.com/'
    ),
    'content_url_regexes' => array(
        "http://www.meizitu.com/a/-?[1-9]\d*.html"
    ),
    'list_url_regexes' => array(
        "http://www.meizitu.com/a/+[A-Za-z]+.html"
    ),
    'max_try' => 5,
    'fields' => array(
        array(
            // 抽取内容页的文章标题
            'name' => "title",
            'selector' => "//*[@id=\"maincontent\"]/div[1]/div[1]/h2/a",
            'required' => true
        ),
        array(
            //获取tag
            'name' => "tag",
            'selector' => "//*[@id=\"maincontent\"]/div[1]/div[1]/p",
        ),
        array(
//            获取图片地址
            'name' => "img_url",
            'selector' => "//*[@id=\"picture\"]/p/img[@src]",
            'required' => true,
            'repeated' => true
        ),
        array(
//            获取文章信息
            'name' => "info",
            'selector' => "//*[@id=\"maincontent\"]/div[2]/div[2]/p",
            'required' => false
        )
    ),
    'log_show' => false,
    'log_file' => 'D:/imgtest/meizitu.log'
);
$spider = new phpspider($configs);


$spider->on_extract_page = function ($data) {

    $imgs_url=$data['img_url'];
    $title=$data['title'];
    $title=iconv("utf-8","gb2312",$title);
    $imgfloder="D:/imgtest/".$title."/";
    if(!file_exists($imgfloder)){
        mkdir($imgfloder);}
    foreach ($imgs_url as $img){
        $filename=uniqid().'.jpg';
        $imgpath=$imgfloder."/".$filename;
        $imgdata=file_get_contents($img);
        file_put_contents($imgpath,$imgdata);
    }

    return $data;
};



$spider->start();