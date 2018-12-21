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
       $id = input('resume_id');
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
      $data['ct_user'] = Session::get('user_info')['uname'];
      $comm = new CommunicateModel();
      $res = $comm->add($data);
      if ($res) {
         $data['id'] = $res;
         return json(['msg' => '添加成功','code' => 0,'data' => $data]);
       }
       else{
         return json(['msg' => '添加失败','code' => 1,'data' => []]);
       }
    }

    public function editComm(){
      //修改沟通
      $data = input('post.');
      $data['mfy_user'] = Session::get('user_info')['uname'];
      $comm = new Communicate();
      $res = $comm->edit($data);
      $data = $comm->getOne(['id' => $res]);
      if ($data && $res) {
         return json(['msg' => '修改成功','code' => 0,'data' => []]);
       }
       else{
         return json(['msg' => '修改失败,请刷新','code' => 1,'data' => []]);
       }
    }

}
