<?php
namespace app\base;

use think\Controller;

class BaseController extends \think\Controller
{
    protected $keyConfig;
    
    public function __construct(){
        parent::__construct();
        if ($this->keyConfig == null) {
            $this->keyConfig = require ('apps/config/keyConfig.php');
            // var_dump($this->table);
        }
        $this->assign("title",$this->keyConfig['title']);
        $this->assign("subtitle",$this->keyConfig['subtitle']);
        $this->assign("description",$this->keyConfig['description']);
        $this->assign("keyWords",$this->keyConfig['keyWords']); 
        $this->assign("taobao_img_url",$this->keyConfig['taobao_img_url']);
    }
}

?>