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
    
    private $isCollection=false;
    
    

    // private $_m_h5_tk="0d108efaf4053934489d0a0c8744a58e_1503981225329";
    // private $_m_h5_tk_enc="aba7060bd7c5c889d0a906e647fd4ab1";
   

    public function getData($keyWord, $page = 1, $pageSize = 100,$isCollection=false)
    {
        $this->keyWords = $keyWord;
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->isCollection=$isCollection;
        
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
        
        if ($keywords_details != null) {
            if ($keywords_details[TableUtils::getTableDetails('keywords_details', 'update_time')] + $this->keyConfig['cache_time'] < time()) {
                
                $json = $this->netData();
                $jsonObj = json_decode($json, true);
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
            }
        } else {
            
            $json = $this->netData();
            $jsonObj = json_decode($json, true);
            if ($jsonObj) {
                if($this->isCollection){
                    $num=$this->keyConfig['keyWordCollectionPageSize']%$this->keyConfig['keyWordPageSize']>0?($this->keyConfig['keyWordCollectionPageSize']/$this->keyConfig['keyWordPageSize'])+1:$this->keyConfig['keyWordCollectionPageSize']/$this->keyConfig['keyWordPageSize'];
                    $size=$jsonObj['data']['data']['auctionList']['auctions'];
                    for($j=0;$j<$num;$j++){
                        $arr=[];
                        $m=0;
                        for($i = 0; $i <$this->keyConfig['keyWordCollectionPageSize'] ; $i ++){
                            $arr[$i]=$jsonObj['data']['data']['auctionList']['auctions'][$m];
   
                            $m++;
                            if($m>=$size){
                                break 2;
                            }
                        }
                        $data = [
                            TableUtils::getTableDetails('keywords_details', 'keyword_id') => $keyword_id,
                            TableUtils::getTableDetails('keywords_details', 'json') => json_encode($arr),
                            TableUtils::getTableDetails('keywords_details', 'page') => $this->page+$j,
                            TableUtils::getTableDetails('keywords_details', 'page_size') => count($arr),
                            TableUtils::getTableDetails('keywords_details', 'update_time') => time()
                        ];
                        
                        Db::table(TableUtils::getTableDetails('keywords_details'))->insert($data);
                        
                    }
                    
                    if($this->pageSize==$size){
                        return true;
                    }else{
                        return false;
                    }
                    
                }else{
                    $data = [
                        TableUtils::getTableDetails('keywords_details', 'keyword_id') => $keyword_id,
                        TableUtils::getTableDetails('keywords_details', 'json') => $json,
                        TableUtils::getTableDetails('keywords_details', 'page') => $this->page,
                        TableUtils::getTableDetails('keywords_details', 'page_size') => $this->pageSize,
                        TableUtils::getTableDetails('keywords_details', 'update_time') => time()
                    ];
                    
                    Db::table(TableUtils::getTableDetails('keywords_details'))->insert($data);
                }
                
                
                
                $this->addItemDb(true, $keyword_id, $jsonObj);
            }
        }
    }

    private function netData()
    {
        $key = require ('apps/config/keyConfig.php');
        $taobaoke_keyword = $key['taobaoke_keyword'];
        $taobaoke_keyword_data = $key['taobaoke_keyword_data'];
        // var_dump(sprintf($taobaoke_keyword_data,"哈哈","哈哈"));
        $handlePageSize=($this->isCollection)?$this->keyConfig['keyWordCollectionPageSize']:$this->pageSize;
       
        
        $parameter = [
            // 'is_proxy'=>true,
            'header_type' => 1,
            'isHttps' => true,
            'isTbk' => true,
            'is_proxy' => true,
            'taobaoke_keyword' => $taobaoke_keyword,
            'taobaoke_keyword_data' => sprintf($taobaoke_keyword_data, $this->keyWords, $this->page * $handlePageSize, $handlePageSize, $key['pid'], $key['pid'])
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
    
    
    public function getList($id,$page=1,$pageSize=$this->keyConfig['keyWordPageSize']){
        $keywords_details = Db::table(TableUtils::getTableDetails('keywords_details'))
        ->where(TableUtils::getTableDetails('keywords_details', 'id'), $id)
        ->where(TableUtils::getTableDetails('keywords_details', 'page'), $this->page)
        ->select();
        return $keywords_details;
    }
    
    
    public function getGoodsItems($id){
        $keywords_details = Db::table(TableUtils::getTableDetails('goods_list'))
        ->where(TableUtils::getTableDetails('goods_list', 'id'), $id)
        ->select();
        return $keywords_details;
    }
    
    
}

?>