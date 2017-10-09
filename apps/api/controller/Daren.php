<?php
namespace app\api\controller;

use app\base\BaseController;
use think\Request;


class Daren  extends BaseController{
    public function getDaRenUrl(){
        $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : "0";
        $daRen = new \app\api\model\DaRenModel();
        $nextData = $daRen->getDaRen($id);
        if($nextData!=null){
            $array=[
                'url'=>$nextData['darenUrl'],
                'userId'=>$nextData['userId'],
            ];
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Success'],
                $jsonKeyValConfig['Code']=>0,//
                'data'=>$array,
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
    
    public function addDaRenUrlForList(){
        $jsonObj = isset($_REQUEST['data']) ? $_REQUEST['data'] : "";
        if(empty($jsonObj)){
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['msg']=>":数据为空",
                $jsonKeyValConfig['Code']=>-1,//
            ];
            //var_dump(isset($_POST['data']));
            echo json_encode($data);
            return ;
        }
        
        $successCount=0;
        for($i=0;$i<count($jsonObj);$i++){
            $array=[
                'data'=>$jsonObj[$i],
            ];
            $table =new  \app\tableUtils\darenUtils();
            $status = $table->addDaRen($array);
            if($status){
                $successCount++;
            }
        }
        
        if($successCount==count($jsonObj)){
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Success'],
                $jsonKeyValConfig['msg']=>"成功",
                $jsonKeyValConfig['Code']=>0,//
            ];
            echo json_encode($data);
        }else{
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['msg']=>"插入失败",
                $jsonKeyValConfig['Code']=>-1,//
            ];
            echo json_encode($data);
        }
        #echo $data;
    }
}