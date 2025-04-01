<?php

namespace Libs;

define('EXPIRE_TIME',86400);

class cache{

    public function __construct()
    {
        if(!file_exists(CACHE_PATH)){
            if(!mkdir(CACHE_PATH,0755,true)){
                exit("mkdir cache dir");
            }
        }
    }

    public function check($url,$expireTime = EXPIRE_TIME){
        $cacheFile = $this->cacheFileName($url);
        if(
            $cacheFile 
            && file_exists($cacheFile)
            && filesize($cacheFile) > 0
            && (time() - filemtime($cacheFile) <= $expireTime)
        ){
            return file_get_contents($cacheFile);
        }else{
            return false;
        }
    }

    public function save($url,$content){
        $cacheFile = $this->cacheFileName($url);
        file_put_contents($cacheFile,$content);
    }

    public function get($url){
        $cacheFile = $this->cacheFileName($url);
        return file_get_contents($cacheFile);
    }   

    public function cacheFileName($url){
        $t = str_replace([':','/','?','=','&'],'_',$url);
        $t =  ROOT_PATH . '/cache/' . $t . '.html';
        if(strlen($t) > 224){
            exit("cache file name exceed max");
        }
        return $t;
    }
}