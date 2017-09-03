<?php
namespace app\api\controller;
use think\Db;
use app\utils\TableUtils;
use app\base\BaseController;

class ProxyIp  extends BaseController{
    public function getAllList(){
        $list = Db::table(TableUtils::getTableDetails('proxy_ip'))->where(TableUtils::getTableDetails('proxy_ip','status'),0)->select(); // 
        echo json_encode($list);
    }
    
    public function getNextProxyIp(){
        $id = isset($_REQUEST['id']) && ! empty($_REQUEST['id']) ? $_REQUEST['id'] : "1";
        $ProxyIp = Db::table(TableUtils::getTableDetails('proxy_ip'))->where(TableUtils::getTableDetails('proxy_ip','id'),'>',$id)
        ->where(TableUtils::getTableDetails('proxy_ip','status'),0)
        ->order(TableUtils::getTableDetails('proxy_ip','id').' desc')->limit(1)->find(); // 
        echo json_encode($ProxyIp);
    }
    
    public function setProxyIpStatus(){
        $id = isset($_REQUEST['id']) && ! empty($_REQUEST['id']) ? $_REQUEST['id'] : "";
        $status = isset($_REQUEST['status']) && ! empty($_REQUEST['status']) ? $_REQUEST['status'] : "";
        $jsonKeyValConfig=require_once 'apps/utils/jsonKeyValConfig.php';
        if(empty($id) || empty($status)){
            $data=[
                $jsonKeyValConfig['msg']=>$jsonKeyValConfig['msg'].":id,status",
                $jsonKeyValConfig['code']=>-1,//
            ];
            echo json_encode($data);
            return ;
        }
        $data=[
            TableUtils::getTableDetails('proxy_ip','status')=>$status,
        ];
        $update = Db::table(TableUtils::getTableDetails('proxy_ip'))->where(TableUtils::getTableDetails('id','id'),$id)->update($data);
        if($update){
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Success'],
                $jsonKeyValConfig['msg']=>"成功",
                $jsonKeyValConfig['code']=>0,//
            ];
            echo json_encode($data);
        }else{
            $data=[
                $jsonKeyValConfig['Status']=>$jsonKeyValConfig['Fail'],
                $jsonKeyValConfig['msg']=>$jsonKeyValConfig['msg'].":更新失败",
                $jsonKeyValConfig['code']=>-1,//
            ];
            echo json_encode($data);
        }
    }
    
}

?>