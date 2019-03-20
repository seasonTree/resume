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

//非登录后的权限 -----------------------------------
Route::post('api/user/login','Login/login');//登录
Route::post('api/user/logout','User/logOut');//退出登录
//-----------------------------------------------

//登录后的权限-------------------------------------
Route::get('api/dashboard/get','Index/indexList');//获取简历内容
Route::get('api/user/get_user_info','User/getUserInfo');//获取用户信息
Route::get('api/user/get_user_permission','User/getUserPermission');//获取用户权限
Route::post('api/user/change_password','User/changePassword');//修改自己的密码
Route::post('api/user/update_avatar','User/updateAvatar');//更新用户自己的头像
//-----------------------------------------------

//测试使用----------------------------------------
Route::get('test','Resume/test');//测试用
//-----------------------------------------------

//简历api-----------------------------------------
Route::get('api/resume/list','Resume/getResumeList');//获取简历列表，分页，数据放到row字段下，同时要返回total总记录数
Route::post('api/resume/analyze','Resume/getResumeData');//分析简历内容
Route::post('api/resume/add','Resume/addResume');//新增简历
Route::post('api/resume/edit','Resume/editResume');//修改简历
Route::get('api/resume/get_by_id','Resume/getResumeOne');//根据id获取简历内容
Route::post('api/resume/del','Resume/delResume');//删除简历
Route::get('api/communication/list','Communicate/commList');//获取沟通列表
Route::post('api/communication/add','Communicate/addComm');//添加沟通信息
Route::post('api/communication/edit','Communicate/editComm');//修改沟通列表
Route::get('api/communication/get_by_id','Communicate/getComm');//根据id获取一条沟通信息
Route::get('api/communication/get_by_uname','Communicate/getByUname');//根据用户名获取招聘的数据
Route::get('api/resume/get_upload_file','Resume/uploadList');//获取附件列表
Route::post('api/resume/upload_file','Resume/upload');//添加简历附件
Route::post('api/resume/del_file','Resume/delFile');//删除简历附件
Route::post('api/resume/batch_add','Resume/importResume');//批量插入
Route::get('api/resume/check_name','Resume/checkName');//检查重名的简历
Route::post('api/resume/upload_excel','Resume/uploadResume');//上传excel简历
Route::get('api/resume/download','Resume/downloadResume');//下载简历
//-----------------------------------------------

//用户api-----------------------------------------
Route::get('api/user/list','User/lstPage');
Route::post('api/user/add','User/add');
Route::post('api/user/edit','User/edit');
Route::post('api/user/del','User/del');
Route::post('api/user/change_user_passwd','User/changeUserPasswd');
Route::post('api/user/change_status','User/changeStatus');
Route::post('api/user/get_by_id','User/getOne');
Route::post('api/user/change_user_avatar','User/updateAvatarByAdmin'); //用管理员修改用户的头像
Route::get('api/user/all_list','User/lst');
//-----------------------------------------------

//角色api----------------------------------------
Route::get('api/role/list','Role/lstPage');
Route::post('api/role/add','Role/add');
Route::get('api/role/get_check_permission','Role/getRolePri');
Route::post('api/role/set_role_permission','Role/setRolePri');
Route::post('api/role/edit','Role/edit');
Route::post('api/role/del','Role/del');
Route::post('api/role/get_by_id','Role/getOne');
Route::get('api/role/get_user_by_id','Role/getUserById');
Route::post('api/role/set_role_user','Role/setRoleUser');
//-----------------------------------------------

//权限api-----------------------------------------
Route::get('api/permission/list','Privilege/lst');
Route::post('api/permission/add','Privilege/add');//添加权限信息
Route::post('api/permission/get_by_id','Privilege/getOne');//获取一条权限信息
Route::post('api/permission/edit','Privilege/edit');//修改权限信息
Route::post('api/permission/del','Privilege/del');//删除某条权限信息
Route::post('api/permission/sort','Privilege/sort'); //排序
//-----------------------------------------------

//报表权限#############################################################

//个人招聘统计api----------------------------------
Route::get('api/report/person_recru/candidate_list','Report/candidateList');//获取个人招聘统计候选人跟踪报表
Route::get('api/report/person_recru/recruitment_list','Report/recruitmentList');//获取 招聘负责人明细的报表
Route::get('api/report/person_recru/recruitment_total','Report/recruitmentTotal');//招聘负责人统计汇总
Route::get('api/person_recru/export','Report/export');//导出
//-----------------------------------------------

//获取职业分类
Route::get('api/position_cate/get_all','Resume/getJob');
Route::post('api/position_cate/get_by_id','Resume/getJobById');
Route::get('api/position_cate/list','Resume/JobList');
Route::post('api/position_cate/add','Resume/addJob');
Route::post('api/position_cate/edit','Resume/editJob');
Route::post('api/position_cate/del','Resume/delJob');
Route::get('api/resume/export','Resume/export');

//客户相关
Route::get('api/client/list','Client/getList');//客户列表
Route::post('api/client/add','Client/addClient');//添加客户
Route::post('api/client/del','Client/delClient');//删除客户
Route::post('api/client/get_by_id','Client/getCliById');//获取单条
Route::post('api/client/change_status','Client/changeStatus');//改变状态
Route::post('api/client/edit','Client/editClient');//修改
Route::get('api/client/get_all','Client/getAll');//获取所有
Route::get(':name','Index/index');
Route::post(':name','Index/index');
//###################################################################


return [
	// 'api/user/login' => ['Login/login',['method' => 'post']],//写法2

];

