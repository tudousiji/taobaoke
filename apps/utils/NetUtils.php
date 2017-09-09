<?php
namespace app\utils;

use think\Db;
require_once 'apps/utils/function.php';


class NetUtils
{

    

    // 网络请求
    public static function curlData($RequestType = 'GET', $url, $parameter/* ,$post_data =array(),$is_proxy,$header="0",$cookie="",$isHttps=false */)
    {
        if ((! isset($url) && ! (isset($parameter['isTbk']) && ! empty($parameter['isTbk']) && $parameter['isTbk'])) || ! isset($RequestType) || (isset($url) && empty($url) && ! (isset($parameter['isTbk']) && ! empty($parameter['isTbk']) && $parameter['isTbk'])) || (isset($RequestType) && empty($RequestType))) {
            echo "url empty or RequestType empty";
            return;
        }
        $post_data = isset($parameter['post_data']) && is_array($parameter['post_data']) && count($parameter['post_data']) > 0 ? $parameter['post_data'] : array();
        $is_proxy = isset($parameter['is_proxy']) ? $parameter['is_proxy'] : false;
        $header_type = isset($parameter['header_type']) ? $parameter['header_type'] : 0;
        $cookie = isset($parameter['cookie']) ? $parameter['cookie'] : "";
        $isHttps = isset($parameter['isHttps']) ? $parameter['isHttps'] : false;
        
        $tk = null;
       
        if (isset($parameter['isTbk']) && $parameter['isTbk']) {
            $url = trim($parameter['taobaoke_keyword'] . urlencode($parameter['taobaoke_keyword_data']));
            $tk = Db::table(TableUtils::getTableDetails('tbk_token'))->where(TableUtils::getTableDetails('tbk_token', 'status'), 0)
                ->order('rand()')
                ->limit(1)
                ->find();
              
            if (isset($tk)) {
                
                $tk_preix = null;
                if (isset($tk) && isset($tk[TableUtils::getTableDetails('tbk_token', 'tk')]) && ! empty($tk[TableUtils::getTableDetails('tbk_token', 'tk')])) {
                    $cookie = $tk[TableUtils::getTableDetails('tbk_token', 'tk')];
                    $tk_preix = explode("_", explode("=", explode(";", $cookie)[0])[1])[0];
                }
                $time = getMillisecond();
                $md5s = $tk_preix . "&" . $time . "&12574478&" . $parameter['taobaoke_keyword_data'];
                $url = trim(sprintf($parameter['taobaoke_keyword'], $time, md5($md5s)) . urlencode($parameter['taobaoke_keyword_data']));
                // echo $url;
            }
        }
        
        $ch = curl_init();
        
        $headers = array();
        $headers[] = 'Host:' . parse_url($url)['host'];
        $headers[] = 'User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:54.0) Gecko/20100101 Firefox/54.0';
        $headers[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $headers[] = 'Accept-Language:zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3';
        $headers[] = 'Accept-Encoding:gzip,deflate';
        $headers[] = 'Connection:keep-alive';
        $headers[] = 'Upgrade-Insecure-Requests:1';
        $headers[] = 'Cache-Control:max-age=0';
        $headers[] = 'HTTP/1.1';
        
        if (isset($cookie) && ! empty($cookie)) {
            
            curl_setopt($ch, CURLOPT_COOKIE, $cookie); // 使用上面获取的cookies
        }
        
        // $headers= array_merge($headers,randIP());
        curl_setopt($ch, CURLOPT_HEADER, $header_type);
        if (strtoupper($RequestType) != 'GET') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        } else {
            if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {
                $parmant = http_build_query($post_data);
                if (substr($url, strlen($url) - 1, strlen($url)) == "?" || substr($url, strlen($url) - 1, strlen($url)) == "&") {
                    $url = $url . $parmant;
                } else if (strpos($url, "?") !== false) {
                    $url = $url . "&" . http_build_query($post_data);
                } else {
                    $url = $url . "?" . http_build_query($post_data);
                }
            }
        }
        
        $proxy_ip = null;
        if ($is_proxy) {
            $proxy_ip = Db::table(TableUtils::getTableDetails('proxy_ip'))->where(TableUtils::getTableDetails('proxy_ip', 'status'), 0)
                ->order('rand()')
                ->limit(1)
                ->find();
            // var_dump($proxy_ip);
            if (! empty($proxy_ip)) {
                
                curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); // 代理认证模式
                curl_setopt($ch, CURLOPT_PROXY, $proxy_ip[TableUtils::getTableDetails('proxy_ip', 'ip')]); // 代理服务器地址
                curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_ip[TableUtils::getTableDetails('proxy_ip', 'port')]); // 代理服务器端口
                                                                                                                // curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); // 使用http代理模式
            }
        }
        
        if ($isHttps || (isset($proxy_ip)) && is_null($proxy_ip) && ! empty($proxy_ip) && isset($proxy_ip[TableUtils::getTableDetails('proxy_ip', 'http_type')]) && $proxy_ip[TableUtils::getTableDetails('proxy_ip', 'http_type')] == 1) {
            // https开启下面这个
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); //存储cookies
        //echo $url;
        $output = curl_exec($ch);
        //var_dump(curl_error($ch));
        $body = "";
        $header = "";
        $jsonKeyValConfig=require 'apps/config/jsonKeyValConfig.php';
        //var_dump(curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200' && $header_type == "1");
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200' && $header_type == "1") {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $response_header = substr($output, 0, $headerSize);
            $body = substr($output, $headerSize);
            //var_dump($body);
            $content = [
                "response_header" => $response_header,
                'post_data' => $post_data,
                'is_proxy' => $is_proxy,
                'proxy_ip' => $proxy_ip,
                'request_header_type' => $header_type,
                'cookie' => $cookie,
                'isHttps' => $isHttps,
                'body' => self::handleBody(trim($body)),
                'requestType' => $RequestType,
                'url' => $url,
                'tokenObj' => isset($tk) && ! is_null($tk) && ! empty($tk) ? $tk : ""
            ];
            
            $content=array_merge($parameter,$content);//$content 必须在后面以便覆盖前面同名key
            
            if (! empty(curl_error($ch))) {
                
                $ref = [
                    'msg' => curl_error($ch),
                    'status' => $jsonKeyValConfig['Fail'],
                ];
                $content['ref'] = $ref;
            } else {
                $ref = [
                    'msg' => '',
                    'status' => $jsonKeyValConfig['Success'],
                ];
                $content['ref'] = $ref;
            }
            curl_close($ch);
            
            return $content;
        }
        
        $content = [
            'post_data' => $post_data,
            'is_proxy' => $is_proxy,
            'proxy_ip' => $proxy_ip,
            'request_header_type' => $header_type,
            'cookie' => $cookie,
            'isHttps' => $isHttps,
            'body' => self::handleBody(trim($output)),
            'requestType' => $RequestType,
            'url' => $url,
            'tokenObj' => isset($tk) && ! is_null($tk) && ! empty($tk) ? $tk : ""
        ];
        $content=array_merge( $parameter,$content);
        if (! empty(curl_error($ch))) {
            
            $ref = [
                'msg' => curl_error($ch),
                'status' => $jsonKeyValConfig['Fail'],
            ];
            
            $content['ref'] = $ref;
        } else {
            $ref = [
                'msg' => '',
                'status' => $jsonKeyValConfig['Success'],
            ];
            $content['ref'] = $ref;
        }
        curl_close($ch);
        return $content;
    }

    public static function parseTaobaokeKeyWords($parameter)
    {
        $jsonKeyValConfig=require 'apps/config/jsonKeyValConfig.php';
        $bodyStr = $parameter['body'];
        //print_r($bodyStr);
        if (isset($bodyStr) && ! empty($bodyStr) && $parameter['ref']['status'] == $jsonKeyValConfig['Success']) {
            $bodyObj = json_decode($bodyStr, true);
          
            if (isset($bodyObj) && is_array($bodyObj) && count($bodyObj) > 0 && isset($bodyObj['ret']) && is_array($bodyObj['ret']) && count($bodyObj['ret']) > 0 && ! empty($bodyObj['ret'][0]) && strtolower(substr($bodyObj['ret'][0], 0, 7)) != strtolower($jsonKeyValConfig['Success'])) {
                preg_match_all("/set\-cookie:([^\r\n]*)/i", $parameter['response_header'], $matches);
                if (isset($matches) && is_array($matches) && count($matches) > 1) {
                    preg_match("/_m_h5_tk=([a-zA-Z0-9_]{0,});/i", $matches[1][0], $_m_h5_tk);
                    preg_match("/_m_h5_tk_enc=([a-zA-Z0-9_]{0,})/i", $matches[1][1], $_m_h5_tk_enc);
                    if(is_array($_m_h5_tk) && count($_m_h5_tk)>0 &&  is_array($_m_h5_tk_enc) && count($_m_h5_tk_enc)>0){
                        $parameter['cookie'] = $_m_h5_tk[0] . $_m_h5_tk_enc[0];
                        $data = [
                            TableUtils::getTableDetails('tbk_token', 'tk') => $parameter['cookie'],
                            TableUtils::getTableDetails('tbk_token', 'status') => 0,
                            TableUtils::getTableDetails('tbk_token', 'update_time') => time()
                        ];
                        if (isset($bodyObj['proxy_ip']) && is_null($bodyObj['proxy_ip']) && is_array($bodyObj['proxy_ip']) && count($bodyObj['proxy_ip']) > 0) {
                            $data['proxy_id'] = $bodyObj['proxy_ip'][TableUtils::getTableDetails('proxy_ip', 'id')];
                        }
                        Db::table(TableUtils::getTableDetails('tbk_token'))->insert($data);
                    }
                   
                    
                    if (isset($parameter['tokenObj']) && ! is_null($parameter['tokenObj']) && ! empty($parameter['tokenObj'])) {
                        $dataUpdate = [
                            TableUtils::getTableDetails('tbk_token', 'status') => 1,
                            TableUtils::getTableDetails('tbk_token', 'update_time') => time()
                        ];
                        // var_dump($parameter['tokenObj']) ;
                        Db::table(TableUtils::getTableDetails('tbk_token'))->where(TableUtils::getTableDetails('tbk_token', 'id'), $parameter['tokenObj'][TableUtils::getTableDetails('tbk_token', 'id')])->update($dataUpdate); // 更新失效的tk
                    }
                }
                //var_dump("再次请求");
                return self::curlData($parameter['requestType'], $parameter['url'], $parameter);
            }
        } else if (isset($parameter['ref']) && isset($parameter['ref']['status']) && $parameter['ref']['status'] == $jsonKeyValConfig['Fail']) {
            
            if (isset($parameter['ref']['msg']) && strpos(trim($parameter['ref']['msg']), 'Could not resolve proxy:') != - 1 && isset($parameter['proxy_ip']['id']) && ! empty($parameter['proxy_ip']['id'])) {
                $dataUpdate = [
                    TableUtils::getTableDetails('proxy_ip', 'status') => 2,
                    TableUtils::getTableDetails('proxy_ip', 'update_time') => time(),
                    TableUtils::getTableDetails('proxy_ip', 'log') => trim($parameter['ref']['msg'])
                ];
                
                Db::table(TableUtils::getTableDetails('proxy_ip'))->where(TableUtils::getTableDetails('proxy_ip', 'id'), $parameter['proxy_ip'][TableUtils::getTableDetails('proxy_ip', 'id')])->update($dataUpdate); // 更新失效的tk
            }
        }
        return $parameter;
    }

    public static function handleBody($body)
    {
        $str = 'mtopjsonp1(';
        if (stripos($body, $str) === 0) {
            $body = substr($body, strlen($str), strlen($body) - 1 - strlen($str));
        }
        return $body;
    }
}

?>