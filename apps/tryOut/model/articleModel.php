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
    
    public function getList($cateId,$page = 1,$pageSize=20){
        $table = new tryoutUtils();
        return $table->getList($cateId,$page,$pageSize);
    }
    
    public function getCount()
    {
        $table = new tryoutUtils();
        return $table->getCount();
    }
}