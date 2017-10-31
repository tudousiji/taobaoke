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
}