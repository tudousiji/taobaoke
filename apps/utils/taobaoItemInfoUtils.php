<?php
namespace app\utils;


class taobaoItemInfoUtils{
    public function autoItemId($itemId,$isCheckExitItemId=true){
        $table= new \app\tableUtils\taobaoItemInfoUtils();
        return $table->autoItemId($itemId,$isCheckExitItemId);
    }
    
    public function getTaobaoInfoList($page = 1, $pageSize = 20, $isOr = true)
    {
        $utils =  new \app\tableUtils\taobaoItemInfoUtils();
        return $utils->getTaobaoInfoList($page,$pageSize,$isOr);
    }
    
    public  function updateAskeverybodyList($data,$itemId){
        $utils =  new \app\tableUtils\taobaoItemInfoUtils();
        return $utils->updateAskeverybodyList($data,$itemId);
    }
    
    public  function updateReasonList($data,$itemId){
        $utils =  new \app\tableUtils\taobaoItemInfoUtils();
        return $utils->updateReasonList($data,$itemId);
    }
    
    public  function updateCommentList($data,$itemId){
        $utils =  new \app\tableUtils\taobaoItemInfoUtils();
        return $utils->updateCommentList($data,$itemId);
    }
}