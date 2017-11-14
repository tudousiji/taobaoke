<?php
namespace app\tableUtils;
use think\Db;
use app\utils\TableUtils;

class buyinventoryUtils{
    public function getCateList(){
        return Db::table(TableUtils::getTableDetails('buyinventory_cate'))->order(TableUtils::getTableDetails('buyinventory_cate', 'id'),"asc")
        ->select();
    }
    
    public function checkEffectiveContentIdList($contentId){
        $table= Db::table(TableUtils::getTableDetails('buyinventory_item_info'))->
        where(TableUtils::getTableDetails('buyinventory_item_info', 'contentId'),"in",$contentId) 
        ->order(TableUtils::getTableDetails('buyinventory_item_info', 'id'),"asc")
        ->column(TableUtils::getTableDetails('buyinventory_item_info', 'contentId'))
        ;
        //$data=$table->select();
        //echo $table->getLastSql();
        return $table;
    }
    
    public function getItemForContentId($contentId){
        return Db::table(TableUtils::getTableDetails('buyinventory_item_info'))
        ->where(TableUtils::getTableDetails('buyinventory_item_info', 'contentId'),$contentId)
        ->find();
    }
    
    public function addbuyInventoryItem($data){
        return $keywords_details = Db::table(TableUtils::getTableDetails('buyinventory_item_info'))
        ->insert($data);
    }
}