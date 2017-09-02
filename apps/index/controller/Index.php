<?php
namespace app\index\controller;

use app\base\BaseController;
use app\index\model\KeyWords;

// require_once 'apps/utils/function.php';
class Index extends BaseController
{

    public function index($name = 'index')
    {
        echo test();
        
        return 'index,' . $name . '！end';
    }

    public function index2($name = 'index2')
    {
        return 'Hello,' . $name . '!';
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
        
        $keywords = new KeyWords($keyWord, $page, $pageSize,$isCollection);
        $keywords->getData();
        
        $this->assign('name', 123);
        return $this->fetch('index');
    }
    
    public function list(){
        return $this->fetch('list');
    }
}

?>
