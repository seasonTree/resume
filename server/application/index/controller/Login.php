<?php
namespace app\index\controller;

use think\facade\Cookie;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;

class Login
{

    public function login()
    {
       dump(input('post.'));
    }

}
