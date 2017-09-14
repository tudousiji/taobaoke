<?php
namespace app\keyWords\controller;

use app\base\BaseController;
use app\keyWords\model\KeyWords;
use think\Request;
require_once 'apps/utils/function.php';
// require_once 'apps/utils/function.php';
class Keyword extends BaseController
{
    public function lists(){
        $keyword_id = isset($_REQUEST['keyword_id']) && ! empty($_REQUEST['keyword_id']) ? $_REQUEST['keyword_id'] : "1";
        $page = isset($_REQUEST['page']) && ! empty($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        
        
        //var_dump($id."--->".$page);
        $KeyWords=new KeyWords();
        $list=$KeyWords->getList($keyword_id,$page);
        $keyWord = $KeyWords->getIdForKeywords($keyword_id);
        if(empty($list) ){
            
            $list = $KeyWords->getData($keyWord['keyword'], $page, $this->keyConfig['keyWordPageSize'],false);
            $this->assign('list',$list );
            
        }else{
            $this->assign('list',is_array($list)?json_decode($list['json'],true):array() );
            
        }
        
        $count = $KeyWords->getCount();
        $this->assign('page', page($page,$count));
        $this->assign('keyWord', $keyWord['keyword'] ) ;
        return $this->fetch('list');
    }

    public function goodsItem()
    {
        $itemId = isset($_REQUEST['itemId']) && ! empty($_REQUEST['itemId']) 
        && is_numeric($_REQUEST['itemId']) ? urlIdcode( $_REQUEST['itemId'],false) : "1";
        $KeyWords=new KeyWords();
        $item=$KeyWords->getGoodsItems($itemId);
    
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
            
            $this->assign('keyWord', $keyWord);
            $this->assign('list', $return);
            $count = $keywords->getCount();
            $this->assign('page', page($page,$count));
            
            $this->assign('myTime','1499097600');
            
            return $this->fetch('list');
        }
        
    }
    
    
}

?>
