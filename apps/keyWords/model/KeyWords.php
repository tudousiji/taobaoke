<?php
namespace app\keyWords\model;

use app\utils\NetUtils;
use think\Db;
use app\base\BaseModel;
use app\utils\TableUtils;

// require_once 'apps/config/keyConfig.php';
require_once 'apps/utils/function.php';

class KeyWords extends BaseModel
{

    private $keyWords = "";

    private $page = 1;

    private $pageSize = 100;

    private $url = "";

    private $isCollection = false;

    // private $_m_h5_tk="0d108efaf4053934489d0a0c8744a58e_1503981225329";
    // private $_m_h5_tk_enc="aba7060bd7c5c889d0a906e647fd4ab1";
    public function __construct()
    {
        parent::__construct();
    }

    public function getData($keyWord, $page = 1, $pageSize = 100, $isCollection = false)
    {
        $this->keyWords = $keyWord;
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->isCollection = $isCollection;
        
        $key = Db::table(TableUtils::getTableDetails('keywords'))->where(TableUtils::getTableDetails('keywords', 'keyword'), $this->keyWords)->find();
        $keyword_id = 0;
        if ($key == null) {
            $data = [
                TableUtils::getTableDetails('keywords', 'keyword') => $this->keyWords,
                TableUtils::getTableDetails('keywords', 'update_time') => time()
            ];
            Db::table(TableUtils::getTableDetails('keywords'))->insert($data);
            $keyword_id = Db::name(TableUtils::getTableDetails('keywords'))->getLastInsID();
        } else {
            $keyword_id = $key['id'];
        }
        
        $keywords_details = Db::table(TableUtils::getTableDetails('keywords_details'))->where(TableUtils::getTableDetails('keywords_details', 'keyword_id'), $keyword_id)
            ->where(TableUtils::getTableDetails('keywords_details', 'page'), $this->page)
            ->
        // where($this->table['keywords_details']['keyword_id'],$keyword_id)->
        find();
        
        if ($keywords_details != null && !$this->isCollection) {
            if ($keywords_details[TableUtils::getTableDetails('keywords_details', 'update_time')] + $this->keyConfig['cache_time'] < time()) {
                
                $json = $this->netData();
                $jsonObj = json_decode($json, true);
                
                $json = json_encode($jsonObj['data']['data']['auctionList']['auctions']);
                if ($jsonObj) {
                    $data = [
                        TableUtils::getTableDetails('keywords_details', 'keyword_id') => $keyword_id,
                        TableUtils::getTableDetails('keywords_details', 'json') => $json,
                        TableUtils::getTableDetails('keywords_details', 'page') => $this->page,
                        TableUtils::getTableDetails('keywords_details', 'page_size') => $this->pageSize,
                        TableUtils::getTableDetails('keywords_details', 'update_time') => time()
                    ];
                    Db::table(TableUtils::getTableDetails('keywords_details'))->where(TableUtils::getTableDetails('keywords_details', 'id'), $keywords_details[TableUtils::getTableDetails('keywords_details', 'id')])->update($data);
                    $this->addItemDb(false, $keyword_id, $jsonObj);
                }
                
                return $jsonObj['data']['data']['auctionList']['auctions'];
            }
            
            return json_decode($keywords_details[TableUtils::getTableDetails('keywords_details', 'json')],true);
        } else {
            $json = $this->netData();
            $jsonObj = json_decode($json, true);
            
            if ($jsonObj && isset($jsonObj['data']) && isset($jsonObj['data']['data']) && isset($jsonObj['data']['data']['auctionList']) && isset($jsonObj['data']['data']['auctionList']['auctions']) && is_array($jsonObj['data']) && is_array($jsonObj['data']['data']) && is_array($jsonObj['data']['data']['auctionList']) && is_array($jsonObj['data']['data']['auctionList']['auctions']) && count($jsonObj['data']) > 0 && count($jsonObj['data']['data']) > 0 && count($jsonObj['data']['data']['auctionList']) > 0 && count($jsonObj['data']['data']['auctionList']['auctions']) > 0) {
                $insertjson = "";
               
                if ($this->isCollection) {
                    
                    $num = $this->keyConfig['keyWordCollectionPageSize'] % $this->keyConfig['keyWordPageSize'] > 0 ? ($this->keyConfig['keyWordCollectionPageSize'] / $this->keyConfig['keyWordPageSize']) + 1 : $this->keyConfig['keyWordCollectionPageSize'] / $this->keyConfig['keyWordPageSize'];
                    $size =count( $jsonObj['data']['data']['auctionList']['auctions']);
                    
                    $m = 0;
                    for ($j = 0; $j < $num; $j ++) {
                        $arr = [];
                        for ($i = 0; $i < $this->keyConfig['keyWordPageSize']; $i ++) {
                            $arr[$i] = $jsonObj['data']['data']['auctionList']['auctions'][$m];
                            $m ++;
                            
                            if ($m >= $size) {
                                break;
                            }
                        }
                        
                        $page=( ($this->keyConfig['keyWordCollectionPageSize']/$this->keyConfig['keyWordPageSize']) * $this->page 
                            - ($this->keyConfig['keyWordCollectionPageSize']/$this->keyConfig['keyWordPageSize']))  +1+ $j;
                        
                       
                        $data = [
                            TableUtils::getTableDetails('keywords_details', 'keyword_id') => $keyword_id,
                            TableUtils::getTableDetails('keywords_details', 'json') => json_encode($arr),
                            TableUtils::getTableDetails('keywords_details', 'page') => $page,
                            TableUtils::getTableDetails('keywords_details', 'page_size') => count($arr),
                            TableUtils::getTableDetails('keywords_details', 'update_time') => time()
                        ];
                        
                        $isUpdate = Db::table(TableUtils::getTableDetails('keywords_details'))->where(TableUtils::getTableDetails('keywords_details', 'keyword_id'), $keyword_id)
                        ->where(TableUtils::getTableDetails('keywords_details', 'page'), ($page))
                        ->where(TableUtils::getTableDetails('keywords_details','keyword_id'),$keyword_id)->
                        find();
                        if ($isUpdate == null) {
                            Db::table(TableUtils::getTableDetails('keywords_details'))->insert($data);
                        } else {
                            //var_dump($data);
                            Db::table(TableUtils::getTableDetails('keywords_details'))->where(TableUtils::getTableDetails('keywords_details', 'id'), $isUpdate[TableUtils::getTableDetails('keywords_details', 'id')])->update($data);
                        }
                        unset($arr);
                    }
                } else {
                    
                    $keywords_details = $jsonObj['data']['data']['auctionList']['auctions'];
                    $insertjson = json_encode($keywords_details);
                    $data = [
                        TableUtils::getTableDetails('keywords_details', 'keyword_id') => $keyword_id,
                        TableUtils::getTableDetails('keywords_details', 'json') => $insertjson,
                        TableUtils::getTableDetails('keywords_details', 'page') => $this->page,
                        TableUtils::getTableDetails('keywords_details', 'page_size') => $this->pageSize,
                        TableUtils::getTableDetails('keywords_details', 'update_time') => time()
                    ];
                    
                    Db::table(TableUtils::getTableDetails('keywords_details'))->insert($data);
                }
                $this->addItemDb(true, $keyword_id, $jsonObj);
                if ($this->isCollection) {
                    if ($this->pageSize == $size) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return $keywords_details;
                }
            } else {
                return array();
            }
        }
    }

    private function netData()
    {
        $key = require ('apps/config/keyConfig.php');
        $taobaoke_keyword = $key['taobaoke_keyword'];
        $taobaoke_keyword_data = $key['taobaoke_keyword_data'];
        // var_dump(sprintf($taobaoke_keyword_data,"哈哈","哈哈"));
        $handlePageSize = ($this->isCollection) ? $this->keyConfig['keyWordCollectionPageSize'] : $this->pageSize;
        
        $parameter = [
            // 'is_proxy'=>true,
            'header_type' => 1,
            'isHttps' => true,
            'isTbk' => true,
            'is_proxy' => true,
            'taobaoke_keyword' => $taobaoke_keyword,
            'taobaoke_keyword_data' => sprintf($taobaoke_keyword_data, $this->keyWords, ($this->page-1) * $handlePageSize, $handlePageSize, $key['pid'], $key['pid'])
        ];
        
        $aa = \app\utils\NetUtils::curlData('GET', '', $parameter);
        $json = \app\utils\NetUtils::parseTaobaokeKeyWords($aa);
        return $json['body'];
    }

    private function addItemDb($isInsert = false, $keyword_id, $jsonObj)
    {
        if (! isset($jsonObj) || $jsonObj == null || $jsonObj['data'] == null || $jsonObj['data']['data'] == null || $jsonObj['data']['data']['auctionList'] == null || $jsonObj['data']['data']['auctionList']['auctions'] == null || ! is_array($jsonObj['data']['data']['auctionList']['auctions']) || count($jsonObj['data']['data']['auctionList']['auctions']) <= 0) {
            return;
        }
        for ($i = 0; $i < count($jsonObj['data']['data']['auctionList']['auctions']); $i ++) {
            $item = $jsonObj['data']['data']['auctionList']['auctions'][$i];
            
            $data = [
                TableUtils::getTableDetails('goods_list', 'keyword_id') => $keyword_id,
                TableUtils::getTableDetails('goods_list', 'itemId') => $item['nid'],
                TableUtils::getTableDetails('goods_list', 'title') => $item['title'],
                TableUtils::getTableDetails('goods_list', 'zkFinalPriceWap') => $item['zkFinalPriceWap'],
                TableUtils::getTableDetails('goods_list', 'biz30Day') => $item['biz30Day'],
                TableUtils::getTableDetails('goods_list', 'couponStartFee') => $item['couponStartFee'],
                TableUtils::getTableDetails('goods_list', 'clickUrl') => $item['clickUrl'],
                TableUtils::getTableDetails('goods_list', 'shareUrl') => $item['shareUrl'],
                TableUtils::getTableDetails('goods_list', 'pictUrl') => $item['pictUrl'],
                TableUtils::getTableDetails('goods_list', 'couponKey') => $item['couponKey'],
                TableUtils::getTableDetails('goods_list', 'couponAmount') => $item['couponAmount'],
                TableUtils::getTableDetails('goods_list', 'couponSendCount') => $item['couponSendCount'],
                TableUtils::getTableDetails('goods_list', 'couponTotalCount') => $item['couponTotalCount'],
                
                TableUtils::getTableDetails('goods_list', 'couponEffectiveStartTime') => strlen($item['couponEffectiveStartTime'])>11? $item['couponEffectiveStartTime']/1000:$item['couponEffectiveStartTime'],
                TableUtils::getTableDetails('goods_list', 'couponEffectiveEndTime') => strlen($item['couponEffectiveEndTime'])>11?$item['couponEffectiveEndTime']/1000:$item['c:ouponEffectiveEndTime'],
                TableUtils::getTableDetails('goods_list', 'provcity') => $item['provcity'],
                TableUtils::getTableDetails('goods_list', 'nick') => $item['nick'],
                
                TableUtils::getTableDetails('goods_list', 'userType') => $item['userType'],
                TableUtils::getTableDetails('goods_list', 'update_time') => time()
            ];
            
            if ($isInsert) {
                Db::table(TableUtils::getTableDetails('goods_list'))->insert($data);
            } else {
                $goods_list = Db::table(TableUtils::getTableDetails('goods_list'))->where(TableUtils::getTableDetails('goods_list', 'itemId'), $item['nid'])->find();
                if ($goods_list == null) {
                    Db::table(TableUtils::getTableDetails('goods_list'))->insert($data);
                } else if (TableUtils::getTableDetails('goods_list', 'update_time') + $this->keyConfig['cache_time'] < time()) {
                    Db::table(TableUtils::getTableDetails('goods_list'))->where(TableUtils::getTableDetails('goods_list', 'id'), $goods_list[TableUtils::getTableDetails('goods_list', 'id')])->update($data);
                }
            }
        }
    }

    public function getList($keyword_id, $page = 1)
    {
        $keywords_details = Db::table(TableUtils::getTableDetails('keywords_details'))->where(TableUtils::getTableDetails('keywords_details', 'keyword_id'), $keyword_id)
        ->where(TableUtils::getTableDetails('keywords_details', 'page'), $page)
            ->find();
        return $keywords_details;
    }

    public function getGoodsItems($itemId)
    {
        $keywords_details = Db::table(TableUtils::getTableDetails('goods_list'))->where(TableUtils::getTableDetails('goods_list', 'itemId'), $itemId)->find();
        return $keywords_details;
    }
    
    public function getCount(){
       $count = Db::table(TableUtils::getTableDetails('keywords_details'))->count();
       return $count;
    }
    
    
    public function getIdForKeywords($keyword_id){
        $data = Db::table(TableUtils::getTableDetails('keywords'))->
        where(TableUtils::getTableDetails('keywords','id'),$keyword_id)
        ->find();
        return $data;
    }
    
    
    //根据关键词获取更多关键词
    public function getSubKeyWords($keyWords,$id){
        if(empty($keyWords) || empty($id) || !is_numeric($id)){
            return ;
        }
        
        $url=sprintf($this->keyConfig['taoBaoKeyWords'],$keyWords) ;
        $parameter = [
            'isHttps' => true,
            'is_proxy' => true,
        ];
        
        $json = \app\utils\NetUtils::curlData('GET', $url, $parameter);
        
        if(!empty($json['body'])){
            $jsonObj=json_decode($json['body'],true);
            
            if(!empty($jsonObj) &&!empty($jsonObj['result']) && is_array($jsonObj['result']) && count($jsonObj['result'])>0 ){
                $data=[
                    'subKeyWords'=>json_encode($jsonObj['result'])
                ];
                $tableUtils =new \app\tableUtils\keywordsUtils();
                $status = $tableUtils->updateKeyword($data, $id);
                //var_dump("请求网络");
                return $jsonObj['result'];
            }else{
                return array();
            }
        } else{
            return array();
        };
    }
    
    //问大家
    public function getAskeverybodyList($itemId,$id){
        $url=sprintf($this->keyConfig['askEverybody_list'],$itemId) ;
        $parameter = [
            'isHttps' => true,
            'is_proxy' => true,
        ];
        
        $json = \app\utils\NetUtils::curlData('GET', $url, $parameter);
        
        
    }
    
    
    //推荐理由
    public function getReasonList($itemId,$id){
        $url=sprintf($this->keyConfig['reason_list'],$itemId) ;
        $parameter = [
            'isHttps' => true,
            'is_proxy' => true,
        ];
        
        $json = \app\utils\NetUtils::curlData('GET', $url, $parameter);
        
        if(!empty($json['body'])){
            $AskeverybodyListArrayJson= trim(mb_convert_encoding($json['body'], "UTF-8","GBK" ));
            if(stripos($AskeverybodyListArrayJson, "json_tbc_rate_summary(")>=0){
                $AskeverybodyListArrayJson=substr($AskeverybodyListArrayJson, strlen("json_tbc_rate_summary("),strripos($AskeverybodyListArrayJson,")")-strlen("json_tbc_rate_summary("));
            };
            $jsonObj=json_decode($AskeverybodyListArrayJson,true);
            //print_r($json['body']);
           
            if(!empty($jsonObj) &&!empty($jsonObj['data']) && is_array($jsonObj['data']) && count($jsonObj['data'])>0
                &&!empty($jsonObj['data']['impress']) && is_array($jsonObj['data']['impress']) && count($jsonObj['data']['impress'])>0 ){
                    $array=[
                        'time'=>time(),
                        'data'=>json_encode($jsonObj['data']['impress']),
                    ];
                    $data=[
                        'reason'=>json_encode($array)
                    ];
                    
                    $tableUtils =new \app\tableUtils\goodslistUtils();
                    $status = $tableUtils->updateReasonList($data, $id);
                    return $array;
            }else{
                return array();
            }
        } else{
            return array();
        };
    }
    
    //评论
    public function getCommentList($itemId,$id,$page=1){
        //$url=sprintf($this->keyConfig['comment_list'],$itemId,$page) ;
        //$url="https://rate.tmall.com/list_detail_rate.htm?itemId=41464129793&sellerId=123&currentPage=1";
        //$url="https://rate.tmall.com/list_detail_rate.htm?itemId=41464129793&sellerId=123&Page=1";
        $url=sprintf($this->keyConfig['comment_list'],$itemId,$page) ;
        $parameter = [
            'isHttps' => true,
            'is_proxy' => false,
            'header_type' => 1,
        ];
        
        //$json = \app\utils\NetUtils::curlData('GET', $url, $parameter);
        $json =  \app\utils\NetUtils::getData($url);
        
        if(!empty($json)){
            
            $AskeverybodyListArrayJson= trim(mb_convert_encoding($json, "UTF-8","GBK" ));
            if(stripos($AskeverybodyListArrayJson, "jsonp_tbcrate_reviews_list(")>=0){
                $AskeverybodyListArrayJson=substr($AskeverybodyListArrayJson, strlen("jsonp_tbcrate_reviews_list("),strripos($AskeverybodyListArrayJson,")")-strlen("jsonp_tbcrate_reviews_list("));
            };
            $jsonObj=json_decode($AskeverybodyListArrayJson,true);
            
            if(!empty($jsonObj) &&!empty($jsonObj['rateDetail']) && is_array($jsonObj['rateDetail']['rateList']) && count($jsonObj['rateDetail']['rateList'])>0
               ){
                    $array=[
                        'time'=>time(),
                        'data'=>json_encode($jsonObj['rateDetail']['rateList'])
                    ];
                    $data=[
                        'commentList'=>json_encode($array),
                    ];
                    $tableUtils =new \app\tableUtils\goodslistUtils();
                    $status = $tableUtils->updateCommentList($data, $id);
                    //var_dump($status);
                    //var_dump("请求网络");
                    return $array;
            }else{
                return array();
            }
        } else{
            return array();
        };
    }
    
}

?>