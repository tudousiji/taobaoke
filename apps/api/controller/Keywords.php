<?php
namespace app\api\controller;

use app\base\BaseController;



// require_once 'apps/utils/function.php';
class Keywords extends BaseController
{

    public function keywordsId()
    {
        $id = isset($_REQUEST['id']) && ! empty($_REQUEST['id']) ? $_REQUEST['id'] : "1";
        $time=isset($_REQUEST['time']) && ! empty($_REQUEST['time']) ? $_REQUEST['time'] : "";
        $sign=isset($_REQUEST['sign']) && ! empty($_REQUEST['sign']) ? $_REQUEST['sign'] : "";
        
        $jsonKeyValConfig=require_once 'apps/config/jsonKeyValConfig.php';
        
        $isCollection=false;
        if(!empty($time) && !empty($sign) && md5($time)==$sign){
            $pageSize=1000;
            $isCollection=true;
        }else{
            $data=[
                $jsonKeyValConfig['msg']=>$jsonKeyValConfig['sign_error'],
                'code'=>-1,//
            ];
             echo json_encode($data);
             return;
        }
        
        $keywords = new app\keyWords\model\KeyWords();
        $return = $keywords->getData($keyWord, $page, $pageSize,$isCollection);
        
    }

    

    
}

?>
