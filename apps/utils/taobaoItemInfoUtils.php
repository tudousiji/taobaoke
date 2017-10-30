<?php
namespace app\utils;
use app\tableUtils\taobaoItemInfoUtils;

class taobaoItemInfoUtils{
    public function autoItemId($itemId,$isCheckExitItemId=true){
        $table= new taobaoItemInfoUtils();
        $table->autoItemId($itemId,$isCheckExitItemId);
    }
    
    public function getTaobaoInfoList($page = 1, $pageSize = 20, $isOr = true)
    {
        $utils =  new taobaoItemInfoUtils();
        return $utils->getTaobaoInfoList($page,$pageSize,$isOr);
    }
}