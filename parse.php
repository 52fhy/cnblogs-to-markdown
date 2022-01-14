<?php

header("Content-type: text/html; charset=utf-8"); 
include 'vendor/autoload.php';
use Overtrue\Pinyin\Pinyin;

function run(){
	$file = 'CNBlogs_BlogBackup_131_201408_202201.xml';
	$html = file_get_contents($file);

	
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
		// $content = sprintf("---\r\ntitle: %s\r\ndate: %s\r\n---\r\n%s",$title, $datetime, $content);
		$content = sprintf("---\r\ntitle: %s\r\ndate: %s\r\n---\r\n%s",$title, $datetime, $content);
		
		$dir = 'docs/'. date('Y', strtotime($item[3]));

		//转成拼音，防止文件无法创建
		$filename = sprintf('%s/%s-%s.md', $dir, date('Y-m-d', strtotime($item[3])) , $pinyin->permalink($title));
		// $filename = sprintf('%s/%s-%s.md', $dir, date('Y-m-d', strtotime($item[3])) , iconv('utf-8', 'GB2312', $title));

		@mkdir($dir, 0777, true);
		@chmod($filename, 0777);
		
		$content = matchPic($content, $datetime);
		$content = matchPicMD($content, $datetime);
		
		file_put_contents($filename, $content);
		// exit;
	}
}

function matchPic($content, $datetime){
	preg_match_all('/<img src="(.*)" alt=.*>/sU', $content, $pics, PREG_SET_ORDER );
	foreach($pics as $pic){
		$url = $pic[1];
		$path_parts  =  pathinfo($url);
		$extension = @$path_parts['extension'] ? : 'jpg';
 
		if(!preg_match('/^http/', $url)){
			$url = 'http:'.$url;
		}
		
		print_r($url."  ok\n");
		
		if(checkHttpStatus($url)){
			$img_url = downPic($url, $datetime, $extension);
			$content = str_replace($url, $img_url, $content);
		}	
	}
	
	return $content;
}

function matchPicMD($content, $datetime){
	preg_match_all('/!\[\]\((.*)\)/sU', $content, $pics, PREG_SET_ORDER );
	foreach($pics as $pic){
		//print_r($pic);
		$url = $pic[1];
		$path_parts  =  pathinfo($url);
		$extension = @$path_parts['extension'] ? : 'jpg';
 
		if(!preg_match('/^http/', $url)){
			$url = 'http:'.$url;
		}
		
		print_r($url."  ok\n");
		
		if(checkHttpStatus($url)){
			$img_url = downPic($url, $datetime, $extension);
			$content = str_replace($url, $img_url, $content);
		}	
	}
	
	return $content;
}

function downPic($url, $datetime, $extension){
	$extension = $extension ? : 'jpg';
	$content = file_get_contents($url);
	$dir = 'docs/images/';
	
	$filename = $dir.date('Ymd', strtotime($datetime)).uniqid().".".$extension;
	
	@mkdir($dir, 0777, true);
	
	file_put_contents($filename, $content);
	return str_replace('docs','..',$filename);
}

/**
 * 检测图片是否能访问
 * @param $url
 * @return bool
 */
function checkHttpStatus($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "HEAD");
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
	curl_setopt ($ch, CURLOPT_TIMEOUT_MS, 4000);//超时时间
	curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	if(!$info || $info['http_code'] != '200'){
//            print_r($info);
		return false;
	}

	return true;
}

run();

//$content = '及附加费![](http://images2017.cnblogs.com/blog/663847/201710/663847-20171029132658195-1444776911.png) 家具';
//matchPicMD($content, '');