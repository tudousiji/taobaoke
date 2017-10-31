<?php
namespace app\api\controller;
use app\utils\taobaoItemInfoUtils;

class Taobaoinfo{
    public function getTaobaoInfoList()
    {
        $page = isset($_REQUEST['page']) && ! empty($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        $pageSize = isset($_REQUEST['pageSize']) && ! empty($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : "50";
        
        $utils= new taobaoItemInfoUtils();
        $list = $utils->getTaobaoInfoList($page);
        
        
        echo json_encode($list);
    }
}