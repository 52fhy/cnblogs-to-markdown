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
