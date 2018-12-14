<?php
namespace app\index\controller;

use think\facade\Cookie;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;

class User
{

    public function getUserInfo(){
        //用户信息
        $user_info = Session::get('user_info')?Session::get('user_info'):[];
        return json(['code' => 0,'msg' => '获取成功','data' => $user_info]);
    }

    public function logOut(){
        //登出
        session(null);
        return json(['code' => 0,'msg' => '退出成功','data' => []]);
    }

    public function changePassword(){
        //修改密码
        
    }
    public function lst(){
      $data = model('User')->lst();
      return json(['code' => 0,'msg' => '获取数据成功','data' => $data]);
    }
    public function lstPage(){
        $data = input('get.');
        $data = model('User')->lstPage($data['pageIndex'],$data['pageSize']);
        return json(['code' => 0,'msg' => '获取数据成功','data' => $data]);

    }
    public function getOne(){
        $id = input('post.id');
        $data = model('User')->getOne(['id'=>$id]);
        return json(['data'=>$data,'code'=>0,'msg'=>'获取某条数据成功']);
    }
    public function edit(){
        $data =input('post.');
        $data['mfy_time'] = date('Y-m-d H:i:s');
        $id=model('User')->edit($data);
        if($id){
            $data = model('User')->getOne(['id'=>$data['id']]);
            return json(['data'=>$data,'code'=>0,'msg'=>'修改成功']);
        }
    }
    public function add(){
        $data = input('post.');
        $data['passwd'] = hash('sha256',$data['passwd']);
        $id=model('User')->add($data);
        if($id){
            $data = model('User')->getOne(['id'=>$id]);
            return json(['data'=>$data,'code'=>0,'msg'=>'新增成功']);
        }
    }
    public function del(){
        $id = input('post.id');
        if(model('User')->where(['id'=>$id])->delete()){
            return json(['data'=>null,'code'=>0,'msg'=>'删除成功']);
        }
    }
    public function changeStatus(){
        $data = input('post.');
        $data['passwd'] = hash('sha256',$data['passwd']);
        if(model('User')->edit($data)){
            $data = model('User')->getOne(['id'=>$data['id']]);
        }
        return json(['data'=>$data,'code'=>0,'msg'=>'修改状态成功']);
    }
    public function changeUserPasswd(){
        $data = input('post.');
        if(model('User')->edit($data)){
            $data = model('User')->getOne(['id'=>$data['id']]);
        }
        return json(['data'=>$data,'code'=>0,'msg'=>'修改状态成功']);
    }
}
