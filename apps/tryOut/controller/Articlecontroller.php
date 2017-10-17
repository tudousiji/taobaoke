<?php

namespace app\tryOut\controller;

use app\base\BaseController;
use app\tryOut\model\articleModel;
use think\Request;
require_once 'apps/utils/function.php';

class Articlecontroller extends BaseController{
    public function lists(){
        
    }
    
    public function item(){
        $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : "0";
        $articleModel= new articleModel();
        $data = $articleModel->getTryOut($id);
        $content = json_decode($data['data'],true);
        
        $cate = $articleModel->getTryOutCate($data['cate']);
        
        $this->assign("cate",$cate);
        $this->assign("item",$content);
        return $this->fetch("item");
    }
}

?>