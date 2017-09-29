<?php
namespace app\tableUtils;
use think\Db;
use app\utils\TableUtils;
class proxyipUtils{
    public static function addProxtIp($data){
        return $keywords_details = Db::table(TableUtils::getTableDetails('proxy_ip'))
        ->insert($data);
    }
}
?>