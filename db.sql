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
	api1 varchar(1024) not null default '' comment 'api接口1',
	api2 varchar(1024) not null default '' comment 'api接口2',
	api3 varchar(1024) not null default '' comment 'api接口3',
	api4 varchar(1024) not null default '' comment 'api接口4',
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