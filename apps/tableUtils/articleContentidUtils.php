<?php
namespace app\tableUtils;
use think\Db;
use app\utils\TableUtils;

class articleContentidUtils{
    /**
     * 检测重复ContentId
     */
    public function checkRepeatContentId($ids){
        $data = Db::field(TableUtils::getTableDetails('article_contentid', 'contentId'))
        ->table('article_contentid')
        ->union('SELECT '.TableUtils::getTableDetails('article_contentid', 'contentId').' FROM '.TableUtils::getTableDetails('article_contentid'))
        ->union('SELECT '.TableUtils::getTableDetails('article_contentid', 'contentId').' FROM '.TableUtils::getTableDetails('buyinventory_item_info'))
        ->select();
        
        return $data;
    }
    
    public function addContentId($data){
        return $contentId = Db::table(TableUtils::getTableDetails('article_contentid'))
        ->insert($data);
    }
    
}