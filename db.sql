create database if not exists resume default charset utf8 collate utf8_unicode_ci;

use resume;

-- -----------------------------------------------------
-- 用户
-- -----------------------------------------------------
drop table if exists rs_user;
create table rs_user
(
	id bigint(20) unsigned auto_increment primary key comment '自增id',
	uname varchar(64) not null comment '用户名',
	passwd varchar(256) not null comment '密码',
    personal_name varchar(32) not null default '' comment '姓名',
	phone varchar(11) not null default '' comment '联系电话',
    status tinyint(1) not null default 0 comment '0: 正常， 1: 禁用',
	ct_user varchar(64) default '' null comment '创建人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	mfy_user varchar(64) default '' null comment '修改人',
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null comment '修改时间',
	token varchar(255) not null default '' comment '登录token',
	unique index `user_uname` (`uname`)
) engine=InnoDB;

insert into rs_user(uname, passwd) values('admin', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');

-- -----------------------------------------------------
-- 角色
-- -----------------------------------------------------
drop table if exists rs_role;
create table rs_role
(
	id bigint(20) unsigned auto_increment primary key comment '自增id',
	role_name varchar(64) not null comment '角色名称',
	-- status tinyint(1) not null default 0 comment '0: 正常， 1: 禁用',
	ct_user varchar(64) default '' null comment '创建人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	mfy_user varchar(64) default '' null comment '修改人',
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null comment '修改时间'	
);

-- -----------------------------------------------------
-- 用户角色权限（中间表）
-- -----------------------------------------------------
drop table if exists rs_user_role;
create table rs_user_role
(
	user_id bigint(20) unsigned not null comment '用户id',
	role_id bigint(20) unsigned not null comment '角色id'
);

-- -----------------------------------------------------
-- 权限
-- -----------------------------------------------------
drop table if exists rs_permission;
create table rs_permission
(
	id bigint(20) unsigned auto_increment primary key comment '自增id',
	parent_id bigint(20) not null default 0 comment '父的id',
	p_name varchar(64) not null comment '权限名称',
	p_type tinyint(1) not null default 0 comment '0: 菜单， 1: 功能',
	p_icon varchar(32) not null default '' comment '菜单图标，按钮不适用',
	url varchar(1024) not null default '' comment '菜单url',
	p_act_name varchar(64) not null default '' comment '功能英文简写，用于前端匹配功能做权限认证，必须英文，例如add,edit, 按钮名称必须唯一',
	p_component varchar(64) not null default '' comment '加载的模块',
	api varchar(1024) not null default '' comment 'api接口',
	idx smallint(4) not null default 0 comment '菜单排序',
	ct_user varchar(64) default '' null comment '创建人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	mfy_user varchar(64) default '' null comment '修改人',
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null comment '修改时间'	
);

-- -----------------------------------------------------
-- 用户权限
-- -----------------------------------------------------
drop table if exists rs_user_permission;
create table rs_user_permission
(
	role_id bigint(20) unsigned not null comment '角色id',
	p_id  bigint(20) unsigned not null comment '权限id'	
);

-- -----------------------------------------------------
-- 简历
-- -----------------------------------------------------
drop table if exists rs_resume;
create table rs_resume
(
	id bigint(20) unsigned auto_increment primary key comment '简历id',
	name varchar(20) not null default '' comment '姓名',
	phone varchar(20) not null default '' comment '电话',
	birthday varchar(20) not null default '' comment '生日',
	sex varchar(20) not null default '' comment '性别',
	age varchar(20) not null default '' comment '年龄',
	work_year varchar(20) not null default '' comment '工作年限',
	native_place varchar(20) not null default '' comment '户口所在地',
	email varchar(30) not null default '' comment '电子邮箱',
	expected_money_start  int(10) unsigned NOT NULL DEFAULT 0 COMMENT '薪资起始' ,
	expected_money_end  int(10) unsigned NOT NULL DEFAULT 0 COMMENT '薪资终止' ,
	expected_money varchar(30) not null default '' comment '期望薪资',
	nearest_unit varchar(50) not null default '' comment '最近单位',
	nearest_job varchar(50) not null default '' comment '最近职位',
	status varchar(20) not null default '' comment '状态',
	english varchar(20) not null default '' comment '英语水平',
	-- expected_industry varchar(20) not null default '' comment '期望从事行业',
	expected_job varchar(50) not null default '' comment '岗位',
	expected_address varchar(30) not null default '' comment '期望工作地点',
	selfEvaluation text comment '自我介绍',
	educational_background  varchar(100) NOT NULL DEFAULT '' COMMENT '教育背景' ,
	school varchar(30) not null default '' comment '毕业学校',
	educational varchar(10) not null default '' comment '学历',
	speciality varchar(20) not null default '' comment '专业',
	graduation_time varchar(20) not null default '' comment '在校时间',
	skillExpertise text comment '专业技能',
	workExperience text comment '工作经验',
	projectExperience text comment '项目经验',
	certificate text comment '所获证书',
	custom1 text comment '自定义字段1',
	custom2 text comment '自定义字段2',
	custom3 text comment '自定义字段3',
	ct_user varchar(64) default '' not null comment '创建人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	mfy_user varchar(64) default '' not null comment '修改人',
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null comment '修改时间',
	-- is_del tinyint(1) default 0 not null comment '伪删除',
	source varchar(50) default '' not null comment '简历来源',
	-- INDEX `name` (`name`) USING BTREE 
	index `name` (`name`)
);
-- -----------------------------------------------------
-- 上传
-- -----------------------------------------------------
drop table if exists rs_resume_upload;
create table rs_resume_upload
(
	id bigint(20) unsigned auto_increment primary key comment 'id',
	file_name varchar(100) not null default '' comment '文件名',
	resume_url varchar(255) not null default '' comment '简历文件对应的路径',
	ct_user varchar(64) default '' null comment '创建人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	resume_id bigint(20) unsigned not null default 0 comment '简历id'
);

-- -----------------------------------------------------
-- 沟通管理
-- -----------------------------------------------------
drop table if exists rs_communicate;
create table rs_communicate
(
	id bigint(20) unsigned auto_increment primary key comment 'id',
	screen tinyint(1) not null default 0 comment '是否通过筛选,1,是，0否',
	arrange_interview tinyint(1) not null default 0 comment '是否安排面试',
	arrive tinyint(1) not null default 0 comment '是否到场',
	approved_interview tinyint(1) not null default 0 comment '是否通过面试',
	entry tinyint(1) not null default 0 comment '是否入职',
	ct_user varchar(64) default '' null comment '招聘负责人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	mfy_user varchar(64) default '' not null comment '修改人',
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null comment '修改时间',
	resume_id bigint(20) unsigned not null default 0 comment '简历id',
	content varchar(1024) not null default '' comment '具体内容',
	communicate_time varchar(50) not null default '' comment '沟通时间',
	-- INDEX `ct_user` (`ct_user`) USING BTREE 
	index `ct_user` (`ct_user`)
);

-- 插入菜单
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (1, 0, '简历管理', 0, 'fa fa-address-book', '', '', '/resume', '', 0, '', '2018-12-14 13:55:50', 'admin', '2018-12-27 12:35:10');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (2, 1, '简历信息', 0, 'fa fa-address-card', '/resume/index', '', '/resume/Index', '', 1, '', '2018-12-14 13:56:57', 'admin', '2018-12-27 12:35:10');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (3, 2, '查看简历列表数据', 1, '', '', 'resume_list', '', '/api/resume/list', 2, '', '2018-12-14 13:58:37', 'admin', '2018-12-27 12:35:10');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (4, 2, '简历新增', 1, '', '', 'resume_add', '', '/api/resume/add', 5, '', '2018-12-14 13:59:15', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (5, 2, '简历修改', 1, '', '', 'resume_edit', '', '/api/resume/edit', 7, '', '2018-12-14 13:59:52', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (6, 2, '简历删除', 1, '', '', 'resume_del', '', '/api/resume/del', 8, '', '2018-12-14 14:15:13', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (7, 2, '简历单条记录获取(查看，修改使用)', 1, '', '', 'resume_get_row', '', '/api/resume/get_by_id', 6, '', '2018-12-14 14:16:20', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (8, 2, '获取简历沟通列表', 1, '', '', 'resume_commu_list', '', '/api/communication/list', 9, '', '2018-12-14 14:17:25', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (9, 2, '简历沟通新增', 1, '', '', 'resume_commu_add', '', '/api/communication/add', 10, '', '2018-12-14 14:17:58', 'admin', '2018-12-27 15:18:48');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (10, 2, '简历附件上传', 1, '', '', 'resume_file_upload', '', '/api/resume/upload_file', 14, '', '2018-12-14 14:18:38', 'admin', '2018-12-27 15:18:48');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (11, 2, '获取简历附件列表', 1, '', '', 'resume_file_list', '', '/api/resume/get_upload_file', 13, '', '2018-12-14 14:19:36', 'admin', '2018-12-27 15:18:48');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (13, 0, '用户管理', 0, 'fa fa-users', '', '', '/user', '', 19, '', '2018-12-14 14:22:11', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (14, 13, '用户信息', 0, 'fa fa-user-friends', '/user/index', '', '/user/Index', '', 20, '', '2018-12-14 14:23:25', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (15, 13, '用户角色', 0, 'fa fa-users-cog', '/user/role', '', '/role/Index', '', 29, '', '2018-12-14 14:24:07', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (16, 13, '用户权限', 0, 'fa fa-user-shield', '/user/permission', '', '/permission/Index', '', 39, '', '2018-12-14 14:24:29', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (17, 14, '查看用户列表', 1, '', '', 'user_list', '', '/api/user/list', 21, '', '2018-12-14 14:26:32', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (18, 14, '新增用户', 1, '', '', 'user_add', '', '/api/user/add', 22, '', '2018-12-14 14:27:10', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (19, 14, '修改用户', 1, '', '', 'user_edit', '', '/api/user/edit', 24, '', '2018-12-14 14:27:38', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (20, 14, '删除用户', 1, '', '', 'user_del', '', '/api/user/del', 25, '', '2018-12-14 14:28:07', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (21, 14, '修改用户密码', 1, '', '', 'user_change_pass', '', '/api/user/change_user_passwd', 26, '', '2018-12-14 14:29:02', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (22, 14, '修改用户状态', 1, '', '', 'user_change_status', '', '/api/user/change_status', 27, '', '2018-12-14 14:30:01', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (23, 15, '查看角色列表', 1, '', '', 'role_list', '', '/api/role/list', 30, '', '2018-12-14 14:32:09', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (24, 14, '查看用户单条记录(修改)', 1, '', '', 'user_get_row', '', '/api/user/get_by_id', 23, '', '2018-12-14 14:33:04', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (25, 15, '获取角色单条数据(修改)', 1, '', '', 'role_get_row', '', '/api/role/get_by_id', 32, '', '2018-12-14 14:33:51', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (26, 15, '新增角色', 1, '', '', 'role_add', '', '/api/role/add', 31, '', '2018-12-14 14:34:22', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (27, 15, '角色修改', 1, '', '', 'role_edit', '', '/api/role/edit', 33, '', '2018-12-14 14:34:56', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (28, 15, '角色删除', 1, '', '', 'role_del', '', '/api/role/del', 34, '', '2018-12-14 14:35:47', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (29, 15, '设置角色用户', 1, '', '', 'role_user_set', '', '/api/role/set_role_user', 36, '', '2018-12-14 14:44:37', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (30, 15, '设置角色权限', 1, '', '', 'role_permiss_set', '', '/api/role/set_role_permission', 38, '', '2018-12-14 14:46:00', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (31, 15, '获取当前角色的权限(设置使用)', 1, '', '', 'role_permiss_get', '', '/api/role/get_check_permission', 37, '', '2018-12-14 14:47:16', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (32, 15, '获取当前角色的用户列表(设置使用)', 1, '', '', 'role_user_list', '', '/api/role/get_user_by_id', 35, '', '2018-12-14 14:47:56', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (33, 16, '获取权限列表', 1, '', '', 'permiss_list', '', '/api/permission/list', 40, '', '2018-12-14 14:49:12', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (34, 16, '获取权限单条记录(修改)', 1, '', '', 'permiss_get_row', '', '/api/permission/get_by_id', 42, '', '2018-12-14 14:49:55', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (35, 16, '权限新增', 1, '', '', 'permiss_add', '', '/api/permission/add', 41, '', '2018-12-14 14:50:44', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (36, 16, '权限修改', 1, '', '', 'permiss_edit', '', '/api/permission/edit', 43, '', '2018-12-14 14:51:28', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (37, 16, '权限删除', 1, '', '', 'permiss_del', '', '/api/permission/del', 44, '', '2018-12-14 14:52:51', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (38, 16, '权限排序', 1, '', '', 'permiss_sort', '', '/api/permission/sort', 45, '', '2018-12-14 14:53:22', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (39, 0, '报表', 0, 'fa fa-database', '/report', '', '', '', 46, '', '2018-12-18 18:36:06', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (40, 39, '个人招聘统计', 0, 'fa fa-user-friends', '/report/personal_recruitment', '', '/report/personal_recruitment/Index', '', 47, '', '2018-12-18 18:37:39', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (41, 2, '获取简历沟通单条记录(修改)', 1, '', '', 'resume_commu_get_row', '', '/api/communication/get_by_id', 11, '', '2018-12-26 17:36:06', 'admin', '2018-12-27 15:18:48');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (42, 2, '简历沟通修改', 1, '', '', 'resume_commu_edit', '', '/api/communication/edit', 12, '', '2018-12-26 17:36:49', 'admin', '2018-12-27 15:18:48');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (43, 2, '简历附件删除', 1, '', '', 'resume_file_del', '', '/api/resume/del_file', 16, '', '2018-12-26 17:37:25', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (44, 2, '简历批量插入excel文件上传', 1, '', '', 'resume_excel_upload', '', '/api/resume/upload_excel', 17, '', '2018-12-26 17:38:00', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (45, 2, '简历批量插入', 1, '', '', 'resume_batch_add', '', '/api/resume/batch_add', 18, '', '2018-12-26 17:39:13', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (46, 40, '招聘负责人明细', 1, '', '', 'report_person_recru_list', '', '/api/report/person_recru/recruitment_list', 48, 'admin', '2018-12-27 11:31:52', 'admin', '2019-01-03 10:54:21');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (47, 40, '候选人跟踪表', 1, '', '', 'report_person_recru_candidate', '', '/api/report/person_recru/candidate_list', 50, 'admin', '2018-12-27 11:35:44', 'admin', '2019-01-03 10:56:18');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (48, 40, '数据导出excel', 1, '', '', 'report_person_recru_export_excel', '', '/api/person_recru/export', 51, 'admin', '2018-12-27 11:37:47', 'admin', '2019-01-03 10:56:18');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (49, 2, '简历自动识别功能', 1, '', '', 'resume_analyze', '', '/api/resume/analyze', 3, 'admin', '2018-12-27 14:37:49', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (51, 2, '检查重名的简历(新增，修改)', 1, '', '', 'resume_check_name', '', '/api/resume/check_name', 4, 'admin', '2018-12-27 15:18:34', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (52, 2, '下载简历附件里面的文件', 1, '', '', 'resume_file_download', '', '/api/resume/download', 15, 'admin', '2018-12-27 15:19:49', 'admin', '2018-12-27 15:20:03');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (53, 14, '获取所用的用户(设置角色用户)', 1, '', '', 'user_all_list', '', '/api/user/all_list', 28, 'admin', '2018-12-27 15:24:40', 'admin', '2018-12-27 15:31:23');
INSERT INTO resume.rs_permission (id, parent_id, p_name, p_type, p_icon, url, p_act_name, p_component, api, idx, ct_user, ct_time, mfy_user, mfy_time) VALUES (54, 40, '招聘负责人汇总', 1, '', '', 'report_person_recru_total', '', '/api/report/person_recru/recruitment_total', 49, 'admin', '2019-01-03 10:55:58', 'admin', '2019-01-03 10:56:18');