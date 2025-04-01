<?php

namespace Libs;

use Libs\baseService;
use Libs\toolService;
use Libs\shuhaige;
use simple_html_dom;

define('ROOT_PATH',__DIR__);
define('CACHE_PATH',ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' .DIRECTORY_SEPARATOR);
define('COOKIEFILE',ROOT_PATH . DIRECTORY_SEPARATOR . 'cookie.file');

require(ROOT_PATH . '/Libs/simple_html_dom.php' );

spl_autoload_register(
    function($class){
        require_once ROOT_PATH . DIRECTORY_SEPARATOR . lcfirst(str_replace('\\', '/', $class) . '.php');
    }
);


class taskService extends baseService {

    public $targetUrl;
    public $toolService;
    public $instance;

    public $storyTitle ;

    public function __construct($taskObj)
    {
        $this->targetUrl = $taskObj->targetUrl;
        $this->toolService = (new toolService);
        $instance = $this->toolService->getDomain($this->targetUrl);
        $instance = sprintf('\Libs\%s',$instance);
        $this->instance = new $instance();
        if($instance === false){
            exit("instance create failed");
        }
    }

    public function start(){

        $this->getNext($this->targetUrl);
    }

    public function getNext($url,$idx = 0){

        $this->log("todo get page $url");
        $content = $this->getPage($url);

        if($idx == 0){
            $this->storyTitle = $this->instance->getTitle($content);
        }
        
        $chapterTitle = $this->instance->getChapterTitle($content);
        $this->log("chapterTitle : $chapterTitle");

        $chapterContent = $this->instance->getChapterContent($content);
        $nextUrl = $this->instance->getNextUrl($content);

        $this->log("next url $nextUrl");

        if($nextUrl === false){
            exit("task completed");
        }

        $this->instance->save($chapterTitle . PHP_EOL . $chapterContent,$idx,$this->storyTitle);

        $this->getNext($nextUrl,$idx+1);

    }

}

$taskObj = new \stdClass();
$taskObj->targetUrl = 'https://m.shuhaige.net/351880/122733961.html';

(new taskService($taskObj))->start();