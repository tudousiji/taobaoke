<?php
namespace app\tryOut\model;
use app\base\BaseModel;
use app\tableUtils\tryoutUtils;

class articleModel extends BaseModel{
    public function getTryOut($id){
        $table = new tryoutUtils();
        return $table->getTryOutData($id);
    }
    
    public function getTryOutCate($cateId){
        $table = new tryoutUtils();
        return $table->getTryOutCate($cateId);
    }
}