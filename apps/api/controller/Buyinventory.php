<?php
namespace app\api\controller;
use app\tableUtils\buyinventoryUtils;
use app\base\BaseController;

class Buyinventory  extends BaseController{
    public function getCateList(){
        
        $utils= new buyinventoryUtils();
        $list = $utils->getCateList();
        echo json_encode($list);
    }
    
    
    public function addbuyInventoryItem(){
        $json = isset($_REQUEST['data']) ? $_REQUEST['data'] : "";
        $jsonKeyValConfig=require_once 'apps/config/jsonKeyValConfig.php';
        
        if(empty($json)){
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['msg']=>":数据为空",
                $jsonKeyValConfig['Code']=>-1,//
            ];
            //var_dump(isset($_POST['data']));
            echo json_encode($data);
            return ;
        }
        $jsonObj = json_decode($json,true);
        $contentId=$jsonObj['contentId'];
        $data=$jsonObj['data'];
        $cate_id=$jsonObj['cateId'];
        $utils= new buyinventoryUtils();
        $item = $utils->getItemForContentId($contentId);
        if($item==null){
            $array=['contentId'=>$contentId,
                'cate_id'=>$cate_id,
                'data'=>json_encode($data),
            ];
            if(isset($jsonObj['keywords']) && !empty($jsonObj['keywords'])){
                $array['keywords']=$jsonObj['keywords'];
            };
            $utils->addbuyInventoryItem($array);
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Success'],
                $jsonKeyValConfig['msg']=>"成功",
                $jsonKeyValConfig['Code']=>0,//
            ];
            //var_dump(isset($_POST['data']));
            echo json_encode($array);
        }else{
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['msg']=>"数据已经存在",
                $jsonKeyValConfig['Code']=>-1,//
            ];
            //var_dump(isset($_POST['data']));
            echo json_encode($data);
        }
    }
    
    
    public function checkEffectiveContentIdList(){
        $json = isset($_REQUEST['data']) ? $_REQUEST['data'] : "";
        $jsonKeyValConfig=require_once 'apps/config/jsonKeyValConfig.php';
        
        if(empty($json)){
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['msg']=>":数据为空",
                $jsonKeyValConfig['Code']=>-1,//
            ];
            //var_dump(isset($_POST['data']));
            echo json_encode($data);
            return ;
        }
        //$json='{"data": ["200280281051", "200255392994", "200209484457", "200247630794", "200349711555", "200199543066", "200320425359", "200132834634", "200161549393", "200168580985", "200309649589", "200253725242", "200283283699", "200289773602", "200288548968", "200210651116", "200245592868", "200330581025", "200243757959", "200254129002", "200227123923", "200319918101", "200220609243", "200155127819", "200233811677", "200318488178", "200230524685", "200332859536", "200304784052", "200321166747", "200159839031", "200214445007", "200354305795", "200329156487", "200252153131", "200311250446", "200367137924", "200322501047", "200198497344", "200271966483", "200321245791", "200278551147", "200162083533", "200233629729", "200297426056", "200187976746", "200164765352", "200295190880", "200159684299", "200328345929", "200328562037", "200313359080", "200239818959", "200201678008", "200280698066", "200349062572", "200180423177", "200281114860", "200241592818", "200260594953", "200171834942", "200365906535", "200199495665", "200191244438", "200187159942", "200229394801", "200220680900", "200210034536", "200162784837", "200313461053", "200220779088", "200289123741", "200196119401", "200328607026", "200200458357", "200192374716", "200192557072", "200315593291", "200224760665", "200370719660", "200245067552", "200195060954", "200246095225", "200347881189", "200204016370", "200168120077", "200180838674", "200207167644", "200283318801", "200155778614", "200156881479", "200227221063", "200260406775", "200354181532", "200216067994", "200300992737", "200210400835", "200223318530", "200338462611", "200161538415", "200306630075", "200308114032", "200273498265", "200198415632", "200210521015", "200301092576", "200285394407", "200237055540", "200189528435", "200309622555", "200321062666", "200168629332", "200266514178", "200261582251", "200254796247", "200254395293", "200197939312", "200343681438", "200352544173", "200261495680", "200280875166", "200345456847", "200183965911", "200328137540", "200266258412", "200205116901", "200314786007", "200230501917", "200342262696", "200284022375", "200216662809", "200318375281", "200324830416", "200291268136", "200263934845", "200269037041", "200214352219", "200356765942", "200155471229", "200278059891", "200171446834", "200303634131", "200220466855", "200291361348", "200362156884", "200301850754", "200167403126", "200322699566", "200233391931", "200204169846", "200318641488", "200328523161", "200325532233", "200329622195", "200354845095", "200237043591", "200260696892", "200300032787", "200180680296", "200168454576", "200210939388", "200233752902", "200360774674", "200150413609", "200298860597", "200172355932", "200199677418", "200208951614", "200274703149", "200256737257", "200333717411", "200208126063", "200258417757", "200297477386", "200181988090", "200285406945", "200156213855", "200301886709", "200162437915", "200174567954", "200211089111", "200319916943", "200197332086", "200299711869", "200274819813", "200324794506", "200313154266", "200365794332", "200271127231", "200252353103", "200290349440"]}';
       
        $jsonObj = json_decode($json,true);
        $data=$jsonObj['data'];
        $cateId=$jsonObj['cateId'];
        $page=$jsonObj['page'];
        $contentId=implode(',',$data);
        
        $utils= new buyinventoryUtils();
        $list = $utils->checkEffectiveContentIdList($contentId);
        
        $utils= new buyinventoryUtils();
        $cate = $utils->getCateId($cateId);
        $isNextpage=false;
        if($cate['isrepeatover']==0 && $cate['maxpage']<=0){
            $isNextpage=$this->isrepeatover($utils, $data);
        }else if($cate['isrepeatover']==1){//超过指定数就停止
            $isNextpage=$this->isrepeatover($utils, $data);
        }else if($cate['maxpage']>0){
            if($page>=$cate['maxpage']){
                $isNextpage=false;
            }else {
                $isNextpage=true;
            }
        }else{
            $isNextpage=false;
        }
       
        
        $array=['data'=>$list,
            'isNextpage'=>$isNextpage,
        ];
        echo json_encode($list);
    }
    
    private function isrepeatover($utils,$data){
        $repeatCount=0;
        $isNextpage=true;
        for($i=0;$i<count($data);$i++){
            $item = $utils->getItemForContentId($data[$i]);
            if($item!=null){
                $repeatCount++;
            }
            if($repeatCount>=0){
                $isNextpage=false;
                break;
            }
        }
        return $isNextpage
    }
    
}