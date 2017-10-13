<?php
namespace app\api\model;
use app\base\BaseModel;
use app\keyWords\model;
use app\tableUtils\tryoutUtils;

class DaRenModel  extends BaseModel{
    public function insertTryout($data){
        $daren=new tryoutUtils();
        return $daren->addTryout($data);
    }
    
    public function getTryout($itemId=0,$reportId=0){
        $daren=new tryoutUtils();
        return $daren->getTryout($itemId,$reportId);
    }
}