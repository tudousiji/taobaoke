<?php
namespace app\tableUtils;
use think\Db;
use app\utils\TableUtils;

class tryoutUtils{
    public static function addTryout($data){
        return $keywords_details = Db::table(TableUtils::getTableDetails('taobao_try_item'))
        ->insert($data);
    }
    
    public function getTryout($itemId=0,$reportId=0){
        return Db::table(TableUtils::getTableDetails('taobao_try_item'))->
        where(TableUtils::getTableDetails('taobao_try_item', 'itemId'), $itemId)->
        where(TableUtils::getTableDetails('taobao_try_item', 'reportId'), $reportId)
        ->find();
    }
    
    public function getCate(){
        return Db::table(TableUtils::getTableDetails('taobao_try_cate'))->
                select();
    }
    
    public function getTryOutCate($id){
        return Db::table(TableUtils::getTableDetails('taobao_try_cate'))->
        where(TableUtils::getTableDetails('taobao_try_cate', 'id'), $id)
        ->find();
    }
    
    public function getTryOutCateId($cateId){
        return Db::table(TableUtils::getTableDetails('taobao_try_cate'))->
        where(TableUtils::getTableDetails('taobao_try_cate', 'cate_id'), $cateId)
        ->find();
    }
    
    public function getTryOutData($id){
        return Db::table(TableUtils::getTableDetails('taobao_try_item'))->
        where(TableUtils::getTableDetails('taobao_try_item', 'id'), $id)
        ->find();
    }
    
    public function getList($cateid, $page = 1,$pageSize=20)
    {
        $list = Db::table(TableUtils::getTableDetails('taobao_try_item'))
        ->where(TableUtils::getTableDetails('taobao_try_item', 'cate'), $cateid)
        ->limit(($page-1)*$pageSize,$pageSize)
        ->select();
        return $list;
    }
    
    public function getCount($cateId="")
    {
        $tableObg = Db::table(TableUtils::getTableDetails('taobao_try_item'));
        
        if(is_numeric($cateId)){
            $tableObg->where(TableUtils::getTableDetails('taobao_try_item', 'cate'), $cateId);
        }
        $count=$tableObg->count();
        return $count;
    }
    
    public function getRandList($randCount=10){
        return Db::table(TableUtils::getTableDetails('taobao_try_item'))->order('rand()')
        ->limit($randCount)->select();
    }
}
    