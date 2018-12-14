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
// Route::post('api/resume/upload','Resume/upload');//预留上传简历的接口

Route::get('api/resume/list','Resume/getResumeList');//获取简历列表，分页，数据放到row字段下，同时要返回total总记录数
Route::post('api/resume/add','Resume/addResume');//新增简历
Route::post('api/resume/edit','Resume/editResume');//修改简历
Route::get('api/resume/get_by_id','Resume/getResumeOne');//根据id获取简历内容
Route::post('api/resume/del','Resume/delResume');//删除简历
Route::get('api/resume/get_communication','Communicate/commList');//获取沟通列表
Route::post('api/resume/add_communication','Communicate/addComm');//添加沟通信息
Route::get('api/resume/get_upload_file','Resume/uploadList');//获取附件列表
Route::post('api/resume/upload_file','Resume/upload');//添加简历附件
Route::post('api/resume/del_file','');//删除简历附件


Route::post('api/user/login','Login/login');//登录
Route::post('api/user/logout','User/logOut');//退出登录
Route::get('api/user/get_user_info','User/getUserInfo');//获取用户信息
Route::get('api/user/get_user_permission','User/getUserPermission');//获取用户权限
Route::post('api/permission/add','Privilege/add');//添加权限信息
Route::post('api/permission/get_by_id','Privilege/getOne');//获取一条权限信息
Route::post('api/permission/edit','Privilege/edit');//修改权限信息
Route::post('api/permission/del','Privilege/del');//删除某条权限信息
Route::get('api/permission/list','Privilege/lst');
Route::get('api/role/list','Role/lst');
Route::post('api/role/add','Role/add');
Route::post('api/role/edit','Role/edit');
Route::post('api/role/del','Role/del');
Route::get('api/role/get_check_permission','Role/getRolePri');
Route::post('api/role/set_role_permission','Role/setRolePri');
Route::post('api/role/get_by_id','Role/getOne');
Route::get('api/role/get_user_by_id','Role/getUserById');
Route::post('api/role/set_role_user','Role/setRoleUser');
Route::get('api/user/all_list','User/lst');
Route::get('api/user/list','User/lstPage');
Route::post('api/user/add','User/add');
Route::post('api/user/edit','User/edit');
Route::post('api/user/del','User/del');
Route::post('api/user/change_user_passwd','User/changeUserPasswd');
Route::post('api/user/change_status','User/changeStatus');
Route::post('api/user/get_by_id','User/getOne');




return [

	// 'api/user/login' => ['Login/login',['method' => 'post']],//写法2

];

