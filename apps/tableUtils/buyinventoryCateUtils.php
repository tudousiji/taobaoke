<?php
namespace app\tableUtils;
use think\Db;
use app\utils\TableUtils;

class buyinventoryCateUtils{
    public function getCateList(){
        return Db::table(TableUtils::getTableDetails('buyinventory_cate'))->order(TableUtils::getTableDetails('buyinventory_cate', 'id'),"asc")
        ->select();
    }
}