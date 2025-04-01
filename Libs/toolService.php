<?php 

namespace Libs;

class toolService {

    public function getDomain($url){
        // 使用 parse_url 解析 URL
        $parsedUrl = parse_url($url);
        
        // 获取域名部分
        $host = $parsedUrl['host'] ?? '';

        // 使用正则表达式匹配域名部分，并排除后缀
        if ($host) {
            // 去掉顶级域名（后缀），只保留主域名
            $parts = explode('.', $host);
            
            // 排除最后的后缀部分（可能是两个部分的情况，比如 .co.uk）
            $count = count($parts);
            if ($count > 2) {
                // 处理如 .co.uk 的域名
                $_t =  $parts[$count - 2];
            } else {
                // 其他常规情况
                $_t =  $parts[$count - 2];
            }
        }else{
            return false;
        }
        return str_replace('-','',$_t);
    }

}