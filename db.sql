create database if not exists resume default charset utf8 collate utf8_unicode_ci;

use resume;

-- -----------------------------------------------------
-- 用户
-- -----------------------------------------------------
drop table if exists rs_user;
create table rs_user
(
	id bigint(20) auto_increment primary key comment '自增id',
	uname varchar(64) not null comment '用户名',
	passwd varchar(256) not null comment '密码',
    pesonal_name varchar(32) not null default '' comment '姓名',
	phone varchar(11) not null default '' comment '联系电话',
    status tinyint(1) not null default 0 comment '0: 正常， 1: 禁用',
	ct_user varchar(64) default '' null comment '创建人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	mfy_user varchar(64) default '' null comment '修改人',
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null comment '修改时间',
	unique index `user_uname` (`uname`)
) engine=InnoDB;

insert into rs_user(uname, passwd) values('admin', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');

-- -----------------------------------------------------
-- 角色
-- -----------------------------------------------------
drop table if exists rs_role;
create table rs_role
(
	id bigint(20) auto_increment primary key comment '自增id',
	role_name varchar(64) not null comment '角色名称',
	status tinyint(1) not null default 0 comment '0: 正常， 1: 禁用',
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
	user_id bigint(20) not null comment '用户id',
	role_id bigint(20) not null comment '角色id'
);

-- -----------------------------------------------------
-- 权限
-- -----------------------------------------------------
drop table if exists rs_permission;
create table rs_permission
(
	id bigint(20) auto_increment primary key comment '自增id',
	parent_id bigint(20) not null default 0 comment '父的id',
	p_name varchar(64) not null comment '权限名称',
	p_type tinyint(1) not null default 0 comment '0: 菜单， 1: 功能',
	p_icon varchar(32) not null default '' comment '菜单图标，按钮不适用',
	p_act_name varchar(64) not null default '' comment '功能英文简写，用于前端匹配功能做权限认证，必须英文，例如add,edit, 按钮名称必须唯一',
	url varchar(1024) not null default '' comment '菜单url',
	api varchar(1024) not null default '' comment 'api接口',
	idx smallint(4) not null default 0 comment '菜单排序',
	-- top_class varchar(128) not null default '' comment '上级的层级, 逗号分割: 例如 1,2,3', --暂时不用，待讨论
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
	role_id bigint(20) not null comment '角色id',
	p_id  bigint(20) not null comment '权限id'	
);

-- -----------------------------------------------------
-- 简历
-- -----------------------------------------------------
drop table if exists rs_resume;
create table rs_resume
(
	id bigint(20) not null comment '简历id',
	name varchar(20) not null default '' comment '姓名',
	phone varchar(20) not null default '' comment '电话',
	birthday varchar(20) not null default '' comment '生日',
	sex varchar(20) not null default '' comment '性别',
	age varchar(20) not null default '' comment '年龄',
	work_year varchar(20) not null default '' comment '工作年限',
	native_place varchar(20) not null default '' comment '户口所在地',
	email varchar(30) not null default '' comment '电子邮箱',
	expected_money varchar(30) not null default '' comment '期望薪资',
	status varchar(20) not null default '' comment '状态',
	english varchar(20) not null default '' comment '英语水平',
	-- expected_industry varchar(20) not null default '' comment '期望从事行业',
	-- expected_job varchar(20) not null default '' comment '期望从事职业',
	expected_address varchar(30) not null default '' comment '期望工作地点',
	selfEvalation text comment '自我介绍',
	school varchar(30) not null default '' comment '毕业学校',
	educational varchar(10) not null default '' comment '学历',
	speciality varchar(20) not null default '' comment '专业',
	-- graduation_time varchar(20) not null default '' comment '在校时间',
	skillExpertise text comment '专业技能',
	work_experience text comment '工作经验',
	project_experience text comment '项目经验',
	ct_user varchar(64) default '' not null comment '创建人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	mfy_user varchar(64) default '' not null comment '修改人',
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null comment '修改时间'	



);
-- -----------------------------------------------------
-- 上传
-- -----------------------------------------------------
drop table if exists rs_resume_upload;
create table rs_resume_upload
(
	id bigint(20) auto_increment primary key comment 'id',
	file_name varchar(100) not null default '' comment '文件名',
	resume_url varchar(255) not null default '' comment '简历文件对应的路径',
	ct_user varchar(64) default '' null comment '创建人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',

);

-- -----------------------------------------------------
-- 上传
-- -----------------------------------------------------
drop table if exists rs_communicate;
create table rs_communicate
(
	id bigint(20) auto_increment primary key comment 'id',
	screen tinyint(1) not null default 0 comment '是否通过筛选,1,是，0否',
	arrange_interview tinyint(1) not null default 0 comment '是否安排面试',
	arrive tinyint(1) not null default 0 comment '是否到场',
	approved_interview tinyint(1) not null default 0 comment '是否通过面试',
	entry tinyint(1) not null default 0 comment '是否入职',
	ct_user varchar(64) default '' null comment '招聘负责人',
	ct_time datetime default CURRENT_TIMESTAMP not null comment '创建时间',
	mfy_user varchar(64) default '' not null comment '修改人',
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null comment '修改时间',
	resume_id bigint(20) not null default 0 comment '简历id'
);