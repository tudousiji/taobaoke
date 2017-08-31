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
        $page = isset($_REQUEST['page']) && ! empty($_REQUEST['page']) ? $_REQUEST['page'] : "1";
        $pageSize = isset($_REQUEST['pageSize']) && ! empty($_REQUEST['pageSize']) ? $_REQUEST['pageSize'] : "100";
        $keywords = new KeyWords($keyWord, 1, 1000);
        $keywords->getData();
        
        $this->assign('name', 123);
        return $this->fetch('q');
    }
}

?>
