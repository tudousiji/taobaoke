<?php
namespace app\api\controller;

use app\base\BaseController;
use think\Request;
use app\utils\TableUtils;
//淘宝试用
class Tryout extends BaseController{
    public function checkEffectiveTaobaoTryIdListUrl(){
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
        $table =new  \app\tableUtils\tryoutUtils();
        $failCount=0;
        $successArr=array();
        for($i=0;$i<count($jsonObj);$i++){
            $itemId=$jsonObj[$i]['itemId'];
            $reportId=$jsonObj[$i]['reportId'];
            $title=$jsonObj[$i]['title'];
            $status = $table->getTryout($itemId,$reportId);
            if($status && $status!=null && is_array($status) && count($status)>0){
                $failCount++;
            }else{
                $arr=array();
                $arr=[
                    'itemId'=>$itemId,
                    'reportId'=>$reportId,
                    'title'=>$title,
                ];
                $successArr[]=$arr;
                continue;
            }
            if($failCount>=5){
                break;
            }
        }
        $arr=[
            'isNextPage'=>$failCount>=5?false:true,
            'data'=>$successArr
        ];
        echo json_encode($arr);
    }
    
    
    public function addTaobaoTry(){
        #$itemId = isset($_REQUEST['itemId']) ? $_REQUEST['itemId'] : "";
        #$reportId = isset($_REQUEST['reportId']) ? $_REQUEST['reportId'] : "";
        $json=isset($_REQUEST['data']) ? $_REQUEST['data'] : "";
        $jsonKeyValConfig=require_once 'apps/config/jsonKeyValConfig.php';
        
        if(empty($json) ){
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['msg']=>":数据为空",
                $jsonKeyValConfig['Code']=>-1,//
            ];
            //var_dump(isset($_POST['data']));
            echo json_encode($data);
            return ;
        }
        
        $data=json_decode($json,true);
        $itemId=$data['itemId'];
        $reportId=$data['reportId'];
        if(empty($itemId) || empty($reportId) || $itemId=="0" || $reportId=="0"){
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['msg']=>":数据错误",
                $jsonKeyValConfig['Code']=>-1,//
            ];
            //var_dump(isset($_POST['data']));
            echo json_encode($data);
            return ;
        }
        
        $array=[
            'itemId'=>$itemId,
            'reportId'=>$reportId,
            'data'=>json_encode($data['data']),
        ];
        $table =new  \app\tableUtils\tryoutUtils();
        $status = $table->addTryout($array);
        if($status){
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Success'],
                $jsonKeyValConfig['Code']=>0,//
            ];
            echo json_encode($data);
        }else{
            
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['Code']=>-1,//
                
            ];
            echo json_encode($data);
            
        }
    }
}