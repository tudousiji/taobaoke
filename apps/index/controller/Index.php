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

    
}

?>
