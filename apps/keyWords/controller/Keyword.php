<?php
namespace app\keyWords\controller;

use app\base\BaseController;
use app\keyWords\model\KeyWords;

// require_once 'apps/utils/function.php';
class Keyword extends BaseController
{
    public function lists(){
        $id = isset($_REQUEST['id']) && ! empty($_REQUEST['id']) ? $_REQUEST['id'] : "1";
        $KeyWords=new KeyWords();
        $list=$KeyWords->getList($id);
        $this->assign('list',$list);
        return $this->fetch('list');
    }

    public function goodsItem()
    {
        $id = isset($_REQUEST['id']) && ! empty($_REQUEST['id']) ? $_REQUEST['id'] : "1";
        $KeyWords=new KeyWords();
        $item=$KeyWords->getGoodsItems($id);
        $this->assign('item',$item);
        return $this->fetch('item');
    }

   
    public function q()
    {
        $keyWord = isset($_REQUEST['q']) && ! empty($_REQUEST['q']) ? $_REQUEST['q'] : "";
        if (! isset($keyWord) || empty($keyWord)) {
            return;
        }
        $keyConfig=require ('apps/config/keyConfig.php');
        $page = isset($_REQUEST['page']) && ! empty($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        $pageSize = isset($_REQUEST['pageSize']) && ! empty($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : $keyConfig['keyWordPageSize'];
        
        $time=isset($_REQUEST['time']) && ! empty($_REQUEST['time']) ? $_REQUEST['time'] : "";
        $sign=isset($_REQUEST['sign']) && ! empty($_REQUEST['sign']) ? $_REQUEST['sign'] : "";
        
        $isCollection=false;
        if(!empty($time) && !empty($sign) && md5($time)==$sign){
            $pageSize=1000;
            $isCollection=true;
        }
        
        $keywords = new KeyWords();
        $return = $keywords->getData($keyWord, $page, $pageSize,$isCollection);
        
        if($isCollection){
            $jsonKeyValConfig=require_once 'apps/utils/jsonKeyValConfig.php';
            
            if(isset($return) && !empty($return)){
                if($return){
                    $data=[
                        $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Success'],
                        'code'=>0,//还可以请求下一页
                    ];
                    echo json_encode($data);
                }else{
                    $data=[
                        $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Success'],
                        'code'=>1,//不可以请求下一页
                    ];
                    echo json_encode($data);
                }
            }else {
                $data=[
                    $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                    'code'=>-1,//
                ];
                echo json_encode($data);
            }
        }else{
            print_r($return);
            $this->assign('keyWord', $keyWord);
            $this->assign('list', $return);
            return $this->fetch('list');
        }
        
    }
    
    
}

?>
