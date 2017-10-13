<?php
namespace app\tableUtils;
use think\Db;
use app\utils\TableUtils;

class tryoutUtils{
    public static function addTryout($data){
        return $keywords_details = Db::table(TableUtils::getTableDetails('tryout'))
        ->insert($data);
    }
    
    public function getTryout($itemId=0,$reportId=0){
        return Db::table(TableUtils::getTableDetails('tryout'))->
        where(TableUtils::getTableDetails('tryout', 'itemId'), $itemId)->
        where(TableUtils::getTableDetails('tryout', 'reportId'), $reportId)
        ->find();
    }
}