<?php
namespace app\keyWords\controller;
use app\tryOut\model\articleModel;
use app\base\BaseController;
use app\keyWords\model\KeyWords;
use think\Request;
require_once 'apps/utils/function.php';

// require_once 'apps/utils/function.php';
class Keyword extends BaseController
{

    public function lists()
    {
        $keyword_id = isset($_REQUEST['keyword_id']) && ! empty($_REQUEST['keyword_id']) ? $_REQUEST['keyword_id'] : "1";
        $page = isset($_REQUEST['page']) && ! empty($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        
        // var_dump($id."--->".$page);
        $KeyWords = new KeyWords();
        $list = $KeyWords->getList($keyword_id, $page);
        $keyWord = $KeyWords->getIdForKeywords($keyword_id);
        if (empty($list)) {
            $list = $KeyWords->getData($keyWord['keyword'], $page, $this->keyConfig['keyWordPageSize'], false);
            $this->assign('list', $list);
        } else {
            $this->assign('list', is_array($list) ? json_decode($list['json'], true) : array());
        }
        
        $subKeyWordsArr = $keyWord['subKeyWords'];
        $subKeyWords = $keyWord['keyword'];
        if (empty($subKeyWordsArr)) {
            $subKeyWordsArr = $KeyWords->getSubKeyWords($keyWord['keyword'], $keyWord['id']);
        } else {
            $subKeyWordsArr = json_decode($subKeyWordsArr, true);
        }
        
        if (is_array($subKeyWordsArr) && count($subKeyWordsArr) > 0) {
            for ($i = 0; $i < count($subKeyWordsArr); $i ++) {
                $subKeyWords .= ("," . $subKeyWordsArr[$i][0]);
            }
        } else {
            $subKeyWords = $keyWord['keyword'];
        }
        
        $this->assign('keyword_id', $keyword_id);
        $this->assign('subKeyWords', $subKeyWords);
        $count = $KeyWords->getCount();
        $this->assign('page', page($page, $count));
        $this->assign('count', $count*$this->keyConfig['keyWordPageSize']);
        $this->assign('keyWord', $keyWord['keyword']);
        return $this->fetch('list');
    }

    public function goodsItem()
    {
        $itemId = isset($_REQUEST['itemId']) && ! empty($_REQUEST['itemId']) && is_numeric($_REQUEST['itemId']) ? urlIdcode($_REQUEST['itemId'], false) : "1";
        $KeyWords = new KeyWords();
       
        $item = $KeyWords->getGoodsItems($itemId);
        
        if(empty($item['taobao_item_info_itemId'])){//往淘宝信息库里面插入itemid
            $utils=new \app\utils\taobaoItemInfoUtils();
            $utils->autoItemId($item['itemId'],$item['title'],true);
        }
        
        $cate = $KeyWords->getIdForKeywords($item['keyword_id']);
        $this->assign('cate', $cate);
        
        
        $reasonData=null;
        if ($this->keyConfig['is_web_collector'] && (($this->keyConfig['reason_list_cache_time'] != - 1) || empty($item['reason'])) ) {
            if(empty($item['reason'])){
                //var_dump("stmp 1");
                $reasonData = $KeyWords->getReasonList($itemId, $item['id']);
                
            }else{
                //var_dump("stmp 2");
                $array = json_decode($item['reason'],true);
                //var_dump(( $array['time'] <= (time() - $this->keyConfig['reason_list_cache_time'])) );
                if (!$array || !isset($array['time']) ||  ( $array['time'] <= (time() - $this->keyConfig['reason_list_cache_time'])) ) {
                    //var_dump("stmp 3");
                    $reasonData = $KeyWords->getReasonList($itemId, $item['id']);
                }
            }
            
            //$this->assign('reasonList', $reasonList);
        }else{
            $reasonData =json_decode( $item['reason'],true);
        }
        $this->assign('reasonData', $reasonData);
       
        if (isset($reasonData) && !empty($reasonData)
            && isset($reasonData['count']) && !empty($reasonData['count']) &&
            isset($reasonData['count']['total']) && isset($reasonData['count']['bad']) && isset($reasonData['count']['normal']) && isset($reasonData['count']['good']) 
            && $reasonData['count']['bad']!=0 && $reasonData['count']['normal']!=0 && $reasonData['count']['good']!=0
            ){
                $PraiseRate=(1-(($reasonData['count']['normal']+$reasonData['count']['bad'])/$reasonData['count']['good']))*100;
                $this->assign('PraiseRate', $PraiseRate);
                
        }else{
            $this->assign('PraiseRate', 100);
        }
        
        $commentData=null;
        if ($this->keyConfig['is_web_collector'] &&  (($this->keyConfig['comment_list_cache_time'] != - 1)  || empty($item['commentList'])) ) {
            if(empty($item['commentList'])){
                $commentData = $KeyWords->getCommentList($itemId, $item['id']);
            }else{
                $array = json_decode($item['commentList'],true);
                if (!$array || !isset($array['time']) ||  ( $array['time'] <= (time() - $this->keyConfig['comment_list_cache_time'])) ) {
                    $commentData = $KeyWords->getCommentList($itemId, $item['id']);
                }
            }
        }else{
            $commentData =json_decode( $item['commentList'],true);
        }
        //var_dump($commentData);
        //var_dump(isset($commentData) && is_array($commentData) && count($commentData)>0);
        $this->assign('commentData', $commentData);
        
        //var_dump($reasonData);
        if(!empty($reasonData) && is_array($reasonData) && count($reasonData)>0){
            $reasonList= $reasonData['impress'];
            //var_dump($reasonList);
            if(!empty($reasonList) && is_array($reasonList) && count($reasonList)>0){
                $reason="只要".($item['zkFinalPriceWap']-$item['couponAmount']/100)."元超划算,大家觉得";
                for($i=0;$i<count($reasonList);$i++){
                    $reason.=("【".$reasonList[$i]['title']);
                    if($reasonList[$i]['count']>0){
                        $reason.="(".$reasonList[$i]['count']."人)】";
                    }else{
                        $reason.="】  ";
                    }
                    
                };
                if(!empty($commentData) && is_array($commentData) && count($commentData)>0){
                    $commentList=$commentData;
                    
                    if(!empty($commentList) && is_array($commentList) && count($commentList)>0){
                        $reason.="最近大家发表的评论觉得此宝贝:".$commentList[0]['rateContent'];
                    }
                }
                
                $this->assign('reason', $reason);
            }
            
        }
       
        
            $titleKeyWords=array();
            if($this->keyConfig['is_web_collector'] && empty($item['keywords'])){
                $titleKeyWords = $KeyWords->getBaiDuPos($item['title'],$item['id']);
            }
            
            if(!empty($item['keywords'])){
                $titleKeyWords =json_decode( $item['keywords'],true);
                
                $keyWordsCount=count($titleKeyWords);
                
                if($keyWordsCount>0){
                    $itemKeyWords="";
                    for($i=0;$i<$keyWordsCount;$i++){
                        $itemKeyWords.=$titleKeyWords[$i];
                        if($i<($keyWordsCount-1)){
                            $itemKeyWords.=",";
                        }
                    }
                   
                    $this->assign('itemKeyWords', $itemKeyWords);
                }
            }
            
        
        
        
        if(!empty($item['askeverybodyList'])){
            $this->assign('askeverybodyList',json_decode($item['askeverybodyList'],true));
        }
        
        
        $discount=10;
        if(!empty($item['zkFinalPriceWap']) && !empty($item['couponAmount']) ){
            $discount=number_format(($item['zkFinalPriceWap']-($item['couponAmount']/100))/$item['zkFinalPriceWap'],2)*10;
        }
        
        $KeyWordsModel = new \app\keyWords\model\KeyWords(); //
        $randGoodsList = $KeyWordsModel->getRandList(10); // 随机10个商品
        
        $articleModel = new articleModel();
        $randTryList=$articleModel->getRandList(10);//随机10条试用
        if($randTryList!=null){
            for($i=0;$i<count($randTryList);$i++){
                $randTryList[$i]['data']=json_decode($randTryList[$i]['data'],true);
                $randTryList[$i]['introduction'] = "";
                if (isset($randTryList[$i]['data']['overall']['content'])) {
                    $randTryList[$i]['introduction'] = $randTryList[$i]['data']['overall']['content'];
                }
                if ($i == 0) {
                    if (strlen($randTryList[$i]['introduction']) < 650) {
                        if (isset($randTryList[$i]['data']['highlight']) && is_array($randTryList[$i]['data']['highlight']) && count($randTryList[$i]['data']['highlight']) > 0) {
                            $size = count($randTryList[$i]['data']['highlight']);
                            for ($j = 0; $j < $size; $j ++) {
                                $randTryList[$i]['introduction'] .= $randTryList[$i]['data']['highlight'][$j]['content'];
                                if (strlen($randTryList[$i]['introduction']) >= 650) {
                                    break;
                                }
                            }
                        }
                    }
                    if (strlen($randTryList[$i]['introduction']) < 650) {
                        if (isset($randTryList[$i]['data']['conclusion']['cons'])) {
                            $randTryList[$i]['introduction'] .= $randTryList[$i]['data']['conclusion']['cons'];
                        }
                    } else {
                        continue;
                    }
                    if (strlen($randTryList[$i]['introduction']) < 650) {
                        if (isset($randTryList[$i]['data']['conclusion']['pros'])) {
                            $randTryList[$i]['introduction'] .= $randTryList[$i]['data']['conclusion']['pros'];
                        }
                    } else {
                        continue;
                    }
                }
            }
    
        }
       
        
        $this->assign('randTryList', $randTryList);
        $this->assign('randGoodsList', $randGoodsList);
        
        $this->assign('discount', $discount);
        $this->assign('item', $item);
        return $this->fetch('item');
    }

    public function q()
    {
        $keyWord = isset($_REQUEST['q']) && ! empty($_REQUEST['q']) ? $_REQUEST['q'] : "";
        if (! isset($keyWord) || empty($keyWord)) {
            return;
        }
        $keyConfig = require ('apps/config/keyConfig.php');
        $page = isset($_REQUEST['page']) && ! empty($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        $pageSize = isset($_REQUEST['pageSize']) && ! empty($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : $keyConfig['keyWordPageSize'];
        
        $time = isset($_REQUEST['time']) && ! empty($_REQUEST['time']) ? $_REQUEST['time'] : "";
        $sign = isset($_REQUEST['sign']) && ! empty($_REQUEST['sign']) ? $_REQUEST['sign'] : "";
        
        $isCollection = false;
        if (! empty($time) && ! empty($sign) && md5($time) == $sign) {
            $pageSize = 1000;
            $isCollection = true;
        }
        
        $keywords = new KeyWords();
        $return = $keywords->getData($keyWord, $page, $pageSize, $isCollection);
        
        if ($isCollection) {
            $jsonKeyValConfig = require_once 'apps/utils/jsonKeyValConfig.php';
            
            if (isset($return) && ! empty($return)) {
                if ($return) {
                    $data = [
                        $jsonKeyValConfig['Status'] => $jsonKeyValConfig['Success'],
                        'code' => 0 // 还可以请求下一页
                    ];
                    echo json_encode($data);
                } else {
                    $data = [
                        $jsonKeyValConfig['Status'] => $jsonKeyValConfig['Success'],
                        'code' => 1 // 不可以请求下一页
                    ];
                    echo json_encode($data);
                }
            } else {
                $data = [
                    $jsonKeyValConfig['Status'] => $jsonKeyValConfig['Fail'],
                    'code' => - 1 //
                ];
                echo json_encode($data);
            }
        } else {
            
            $this->assign('keyWord', $keyWord);
            $this->assign('list', $return);
            $count = $keywords->getCount();
            $this->assign('page', page($page, $count));
            
            // $this->assign('myTime','1499097600');
            
            return $this->fetch('list');
        }
        
    }
    
    
    //js获取url并跳转
    public function getItemUrl(){
        $itemId = isset($_REQUEST['itemId']) && ! empty($_REQUEST['itemId']) && is_numeric($_REQUEST['itemId']) ? urlIdcode($_REQUEST['itemId'], false) : "";
        if(empty($itemId)){
            $array=[
                'code'=>-1,
                'msg'=>"itemId错误",
            ];
            echo json_encode($array);
            return ;
        }
        
        $keywords = new KeyWords();
        $item = $keywords->getItemUrl($itemId);
        if($item!=null){
            $array=[
                'code'=>0,
                'msg'=>"成功",
                'url'=>!empty($item['shareUrl'])?$item['shareUrl']:$item['clickUrl'],
            ];
            echo json_encode($array);
        }else{
            $array=[
                'code'=>-1,
                'msg'=>"没有查到此id数据",
            ];
            echo json_encode($array);
        }
        
    }
    
    
}

?>
