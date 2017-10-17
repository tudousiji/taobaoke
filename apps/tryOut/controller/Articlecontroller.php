<?php

namespace app\tryOut\controller;

use app\base\BaseController;
use app\tryOut\model\articleModel;
use think\Request;
require_once 'apps/utils/function.php';

class Articlecontroller extends BaseController{
    public function lists(){
        $cateId = isset($_REQUEST['cateId']) && is_numeric($_REQUEST['cateId']) ? $_REQUEST['cateId'] : "0";
        $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        $articleModel= new articleModel();
        $list=$articleModel->getList($cateId,$page);
        
        $count = $articleModel->getCount();
        $this->assign('page', page($page, $count));
        for($i=0;$i<count($list);$i++){
            $list[$i]['data']=json_decode($list[$i]['data'],true);
        }
        $cate = $articleModel->getTryOutCate($cateId);
        $this->assign('cate', $cate);
        $this->assign('list', $list);
        $this->assign('count', $count);
        return $this->fetch('list');
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