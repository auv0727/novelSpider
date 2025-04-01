<?php

namespace Libs;

use Libs\httpService;
use Libs\cache;

class baseService {

    public $cache;

    public function log($msg){

        echo sprintf(
            "%s : %s",date('Y-m-d H:i:s'),$msg
        ).PHP_EOL;
    }

    public function quickGet($url){
        return (new httpService)->quickGet($url);
    }

    public function getPage(
        $url,
        $headers = false,
        $referer = false,
        $isOnlyHeader=false,
        $proxyIp = false,
        $proxyPort = false
    ){
        $this->cache = new cache;
        if($this->cache->check($url)){
            $content = $this->cache->get($url);
        }else{
            $content = $this->httpGet($url,$headers,$referer,$isOnlyHeader,$proxyIp,$proxyPort);
        }
        if(strlen($content)){
            $this->cache->save($url,$content);
        }else{
            exit("content is null,with url : $url");
        }
        return $content;
    }

    public function httpGet(
        $url,
        $headers = false,
        $referer = false,
        $isOnlyHeader = false,
        $proxyIp = false,
        $proxyPort = false
    ){
        $httpInstance = new httpService;

        //设置是否只输出reponse header
        $httpInstance->isOnlyHeader(false);

        //设置header，不设置则使用默认header
        $httpInstance->setHeaders($headers);

        //设置isHeader
        $httpInstance->isOnlyHeader($isOnlyHeader);

        //设置referer
        $httpInstance->setReferer($referer);

        //设置cookie
        $httpInstance->setCookieFile(COOKIEFILE);        

        if($proxyIp && $proxyPort){
            $httpInstance->setProxy($proxyIp,$proxyPort);   
        }

        return $httpInstance->httpGet($url);

    }


}