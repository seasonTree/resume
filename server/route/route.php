<?php
use think\facade\Route;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('test','Resume/test');//测试用
Route::post('api/resume/analyze','Resume/getResumeData');//获取简历内容


Route::post('api/user/login','Login/login');//登录
Route::post('api/user/logout','User/logOut');//退出登录
Route::get('api/user/get_userInfo','User/getUserInfo');//获取用户信息

return [

	// 'api/user/login' => ['Login/login',['method' => 'post']],//写法2

];

