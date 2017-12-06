<?php

namespace app\buyinventory\controller;
use app\base\BaseController;
use app\buyinventory\model\buyinventoryModel;
require_once 'apps/utils/function.php';

class Buyinventory extends BaseController{
    public function lists(){
        $cateId = isset($_REQUEST['cateId']) && is_numeric($_REQUEST['cateId']) ? $_REQUEST['cateId'] : "0";
        $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        $pageSize = isset($_REQUEST['pageSize']) && is_numeric($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : "20";
        $utils=new buyinventoryModel();
        $list = $utils->getItemLists($cateId, $page,$pageSize);
        
        $this->assign("list",$list);
        $this->fetch("list");
    }
    
    
    public function item(){
        $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : "1";
        
        $utils=new buyinventoryModel();
        $item = $utils->getItem($id);
        $item["data"]=json_decode($item['data'],true);
        
        $this->assign("title",$item["data"]["title"]);
        $this->assign("body",$item["data"]);
        $this->assign("item",$item);
        $this->fetch("item");
    }
}