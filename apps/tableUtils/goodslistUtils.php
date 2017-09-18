<?php
namespace app\tableUtils;
use think\Db;
use app\utils\TableUtils;


class goodslistUtils{
    public static function updateAskeverybodyList($data,$id){
        return Db::table(TableUtils::getTableDetails('goods_list'))->where(
            TableUtils::getTableDetails('askeverybodyList', 'id'), $id)
            ->setField($data);
    }
    
}

?>