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
      if (!isset($data['resume_id'])) {
        return json(['msg' => '简历id不存在','code' => 3,'data' => []]);
      }
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
      $comm = new CommunicateModel();
      if (Session::get('user_info')['id'] == 1) {
            //超级管理员不做任何验证可以直接修改
            $res = $comm->edit($data);
            $data = $comm->getOne(['a.id' => $data['id']]);
      }
      else{
          $ct_user = $comm->where(['id' => $data['id']])->value('ct_user');
          if ($ct_user != Session::get('user_info')['uname']) {
             return json(['msg' => '没有权限修改别人的沟通记录.','code' => 500,'data' => []]);
          }
          else{
             $res = $comm->edit($data);
             $data = $comm->getOne(['a.id' => $data['id']]);
          }
      }
      
      if ($data && $res) {
         return json(['msg' => '修改成功','code' => 0,'data' => $data]);
       }
       else{
         return json(['msg' => '修改失败,请刷新','code' => 1,'data' => []]);
       }
    }

    public function getComm(){
      //根据id获取沟通
      $id = input('id');
      $comm = new CommunicateModel();
      $data = $comm->getOne(['a.id' => $id]);
      if ($data) {
         return json(['msg' => '获取成功','code' => 0,'data' => $data]);
      }
      else{
         return json(['msg' => '获取失败','code' => 0,'data' => []]);
      }
    }

    public function getByUname(){
      //查询招聘负责人列表
      $input = input('get.');
      $comm = new CommunicateModel();
      $where = "communicate_time between '$input[dtfm]' and '$input[dtto]' and a.ct_user = '$input[uname]'";
      $data = $comm->getCommByUname($where);
      return json(['msg' => '获取成功','code' => 0,'data' => $data]);
    }

}
