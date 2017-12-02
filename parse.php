<?php

header("Content-type: text/html; charset=utf-8"); 
include 'vendor/autoload.php';

$file = 'CNBlogs_BlogBackup_131_201408_201712.xml';
$html = file_get_contents($file);

use Overtrue\Pinyin\Pinyin;
$pinyin = new Pinyin(); // 默认

preg_match_all('/<item><title>(.*)<\/title>.*<link>(.*)<\/link>.*<pubDate>(.*)<\/pubDate>.*<description>(.*)<\/description><\/item>/sU', $html, $data, PREG_SET_ORDER );

// var_dump($data);

foreach($data as $item){
    // var_dump($item);exit;

    // 文章正文
    $content = str_replace(']]>', '', str_replace('<![CDATA[', '', $item[4]));

    // 过滤非法字符
    $title = preg_replace('/(\/|\\|\:|\*|\?|\"|\<|\>|\|)/', "", $item[1]);

    // 时间
    $datetime = date('Y-m-d H:i:s', strtotime($item[3]));

    //内容合成
    $content = sprintf("# %s\r\n\r\n@date:%s\r\n\r\n%s", $item[1], $datetime, $content);
    
    $dir = 'docs/'. date('Y', strtotime($item[3]));

    //转成拼音，防止文件无法创建
    $filename = sprintf('%s/%s-%s.md', $dir, date('Y-m-d', strtotime($item[3])) , $pinyin->permalink($title));
    // $filename = sprintf('%s/%s-%s.md', $dir, date('Y-m-d', strtotime($item[3])) , iconv('utf-8', 'GB2312', $title));

    @mkdir($dir, 0777, true);
    @chmod($filename, 0777);
    
    file_put_contents($filename, $content);
    // exit;
}
