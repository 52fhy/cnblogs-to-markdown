# cnblogs-to-markdown

说明：必须本身是Markdown格式的转换后才是markdown。暂不支持html转markdown。

1、导出博客园备份文件，例如：CNBlogs_BlogBackup_131_201408_201712.xml   
2、下载`overtrue/pinyin`插件，用于标题转换为拼音:  
``` shell
composer init
composer require overtrue/pinyin
``` 
3、运行命令：
``` shell
php parse.php
```
4、效果：
```  shell
$ ls docs
2014/  2015/  2016/  2017/

$ ls docs/2017/
2017-01-04-PHP-ri-zhi-ya-suo-xia-zai.md
2017-01-04-Python-xue-xi-03-bian-liang-lei-xing.md
2017-01-05-Python-xue-xi-04-tiao-jian-kong-zhi-yu-xun-huan-jie-gou.md
2017-01-06-Python-xue-xi-05-han-shu.md
2017-01-07-Python-xue-xi-06-qie-pian.md
2017-01-09-Python-xue-xi-07-die-dai-qi-sheng-cheng-qi.md
2017-01-11-Python-xue-xi-08-han-shu-shi-bian-cheng.md
2017-01-12-Python-xue-xi-09-mo-kuai.md

```
