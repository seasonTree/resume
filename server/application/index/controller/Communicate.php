<?php
namespace app\index\controller;

use think\facade\Cookie;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;
use app\index\model\User;
use app\index\model\Communicate as CommunicateModel;

class Communicate
{

    public function commList(){
      //获取沟通列表
       $id = input('id');
       $comm = new CommunicateModel();
       $data = $comm->get(['resume_id' => $id]);
       if ($data) {
         return json(['msg' => '获取成功','code' => 0,'data' => $data]);
       }
       else{
         return json(['msg' => '无数据','code' => 1,'data' => []]);
       }
    }

    public function addComm(){
      //添加沟通
      $data = input('post.');
      $comm = new CommunicateModel();
      $res = $comm->add($data);
      if ($data) {
         return json(['msg' => '添加成功','code' => 0,'data' => []]);
       }
       else{
         return json(['msg' => '添加失败','code' => 1,'data' => []]);
       }
    }

}
