<?php

namespace Libs;

use Libs\spiderBase;

class shuhaige implements spiderBase {

    public $domain = 'https://m.shuhaige.net';

    public function getNextUrl($content)
    {
        $htmlObj = str_get_html($content);
        foreach($htmlObj->find("div.pager",0)->find("a") as $node){
            if(preg_match("/下一/",$node->plaintext)){
                return $this->domain . $node->href;
            }
        }
        return false;
    }

    public function getChapterTitle($content){
        $html = str_get_html($content);
        return $html->find("h1.headline",0)->plaintext;
    }

    public function getChapterContent($content)
    {
        $htmlObj = str_get_html($content);
        return $htmlObj->find("div.content",0)->plaintext;
    }

    public function getTitle($content){
        $htmlObj = str_get_html($content);
        return $htmlObj->find("p#bookname",0)->plaintext;
    }
    
    public function save($content,$idx,$title){

        $fileName = sprintf("%s.%s",$title,'.txt');
        if($idx == 0){
            file_put_contents($fileName,$content) ;
        }else{
            file_put_contents($fileName,$content,FILE_APPEND);
        }
    }

}