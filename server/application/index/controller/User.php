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
        Session::clear();
        return json(['code' => 0,'msg' => '退出成功','data' => []]);
    }

    public function changePassword(){
        //修改密码
        
    }

}
