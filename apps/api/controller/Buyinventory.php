<?php
namespace app\api\controller;
use app\tableUtils\buyinventoryCateUtils;
use app\base\BaseController;

class Buyinventory  extends BaseController{
    public function getCateList(){
        
        $utils= new buyinventoryCateUtils();
        $list = $utils->getCateList();
        echo json_encode($list);
    }
}