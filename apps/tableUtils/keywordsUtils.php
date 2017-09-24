<?php
namespace app\tableUtils;
use think\Db;
use app\utils\TableUtils;


class keywordsUtils{
    public static function getKeyword($keyword_id,$page=1){
        return $keywords_details = Db::table(TableUtils::getTableDetails('keywords_details'))->where(TableUtils::getTableDetails('keywords_details', 'keyword_id'), $keyword_id)
        ->where(TableUtils::getTableDetails('keywords_details', 'page'), $page)
        ->find();
    }
    
    public static function updateKeyword($data,$id){
         return Db::table(TableUtils::getTableDetails('keywords'))->where(
            TableUtils::getTableDetails('keywords', 'id'), $id)
            ->setField($data);
    }
    
    public static function getidForKeyword($id){
        return Db::table(TableUtils::getTableDetails('keywords'))->where(
            TableUtils::getTableDetails('keywords', 'id'), $id)
            ->find();
    }
    
    public static function getRandHotSearchWord(){
        return Db::table(TableUtils::getTableDetails('keywords'))->order('rand()')
        ->limit(5)->select();
    }
}
?>