<?php 

namespace Libs;

class httpService {

    public $url ;

    public $headers = [];
    //指示 cURL 请求只获取 HTTP 头部，而忽略响应的主体部分。
    public $isHeader = 0;

    public $httpCode = 200;

    public $proxyMode = false;

    public $proxyIp = '127.0.0.1';

    public $proxyPort = '7890';

    public $referer = false;

    public $useCookieFile = false;

    public $cookieFile = false;


    public function quickGet($url){

        $options = [
            "http" => [
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36\r\n" .
                            "Connection: keep-alive" . 
                            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7" .
                            "Accept-Encoding: gzip, deflate, br, zstd\r\n" . 
                            "Accept-Language: zh-CN,en;q=0.9\r\n".
                            "Cache-Control: no-cache\r\n" . 
                            "Pragma:no-cache\r\n"
            ],
            'ssl' => [
                'verify_peer'      => false,  // 禁用验证对等方证书
                'verify_peer_name' => false,  // 禁用验证对等方证书名称
                'allow_self_signed' => true    // 允许自签名证书
            ]
        ];
        $context = stream_context_create($options);
        $response =  @file_get_contents($url, false, $context);

        if($response === false){
            $error = error_get_last();
            echo "Error: " . $error['message']; // 输出错误信息
            exit();
        }


        if (substr($response, 0, 3) === "\x1F\x8B\x08") {
            // 如果是 gzip 压缩格式，解压缩
            $response = gzdecode($response); // 解压缩
        }
        return $response;

    }

    public function setHeaders($headers = false){
        // if(is_array($headers)){
        //     $this->headers = $headers;
        // }else{
        //     exit('headers must be array');
        // }

        $default = [
            'Connection: keep-alive',
            'Accept: application/json, text/plain, */*',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36',
            'Accept-Encoding: gzip',
            'Accept-Language: zh-CN,zh;q=0.9'
        ];
        $this->headers = $headers ? $headers : $default;
    }    

    public function isOnlyHeader($isHeader = false){
        if($isHeader){
            $this->isHeader = $isHeader;
        }
    }

    public function setReferer($referer){
        if($referer){
            $this->headers[] = "Referer: " . $referer;
        }
    }

    public function setProxy($ip=false,$port=false){
        if($ip || $port){
            $this->proxyMode = true;
            $this->proxyIp = $ip ? $ip : '127.0.0.1';
            $this->proxyPort = $port ? $port : '7890';
        }else{
            $this->proxyMode = false;
        }
    }

    public function unsetProxy(){
        $this->proxyMode = false;
    }

    public function setCookieFile($cookieFile){
        $this->useCookieFile = true;
        $this->cookieFile = $cookieFile;
    }


    public function httpGet($url){

        $this->url = $url;
        $oCurl = curl_init();

        if (stripos($url, "https://") != false) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION,  1); //CURL_SSLVERSION_TLSv1
        }

        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_ENCODING, 'gzip,deflate');

        if($this->isHeader){
            curl_setopt($oCurl,CURLOPT_HEADER,$this->isHeader);
        }
        if($this->referer){
            curl_setopt($oCurl,CURLOPT_REFERER,$this->referer);
        }

        if($this->useCookieFile){
            curl_setopt($oCurl,CURLOPT_COOKIEJAR,$this->cookieFile);
            curl_setopt($oCurl,CURLOPT_COOKIEFILE,$this->cookieFile);
        }
        
        if($this->headers){
            curl_setOpt(
                $oCurl,
                CURLOPT_HTTPHEADER,
                $this->headers
            );
        }        

        if($this->proxyMode){
            curl_setopt($oCurl,CURLOPT_PROXY,$this->proxyIp);
            curl_setOPt($oCurl,CURLOPT_PROXYPORT,$this->proxyPort);
        }

        $sContent = curl_exec($oCurl);
        $info = curl_getinfo($oCurl);
        $this->httpCode = $info['http_code'];

        if($this->httpCode != 200){
            curl_close($oCurl);
            var_dump(curl_error($oCurl));    
        }

        curl_close($oCurl);

        return $sContent;
    }

}