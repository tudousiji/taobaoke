<?php
namespace app\api\controller;

use app\base\BaseController;



// require_once 'apps/utils/function.php';
class Keywords extends BaseController
{

    public function keywordsId()
    {
        $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : "0";
        $time=isset($_REQUEST['time']) && is_numeric($_REQUEST['time']) ? $_REQUEST['time'] : "";
        $sign=isset($_REQUEST['sign']) && !isEmpty($_REQUEST['sign']) ? $_REQUEST['sign'] : "";
        $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        
        $jsonKeyValConfig=require_once 'apps/config/jsonKeyValConfig.php';
        
        $isCollection=true;
        if(!empty($time) && !empty($sign) && md5($time)==$sign){
            $pageSize=1000;
            $isCollection=true;
        }else{
            $data=[
                $jsonKeyValConfig['msg']=>$jsonKeyValConfig['sign_error'],
                'code'=>-1,//
            ];
             //echo json_encode($data);
             //return;
        }
        
        $keywords = new \app\api\model\KeyWordsModel();
        $return = $keywords->getData($id,$page,$isCollection);
        
    }

    

    
}

?>
