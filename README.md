# php-小说爬虫

大部分的小说网站会分段展示，一个章节分两页。

直接获取章节的方法会出现内容缺失。

更优的算法是，从小说的第一章开始，获取小说内容和下一页链接，然后递归调用。

这样就可以在获取内容之后，继续下一页。


# 使用方法

因为每个小说网站的展示方式不同，需要针对处理，所以要定义了spiderBase的接口。

以Libs\shuihaige为例。

需继承 spiderBase.php接口中的3个方法。

getNextUrl()，获取下一页地址。
getChapterContent()，获取小说内容
getChapterTitle，获取小说名称。

解析curl获取到的内容，用到的是simple_html_dom。

保存的实现很简单，就是以小说标题.txt直接保存。







