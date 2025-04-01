<?php

namespace Libs;

interface spiderBase {

     public function getNextUrl($currentUrl);
    
     public function getChapterContent($content);

     public function getChapterTitle($content);
}