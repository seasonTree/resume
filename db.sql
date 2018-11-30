create database if not exists resume default charset utf8 collate utf8_unicode_ci;

use resume;

-- -----------------------------------------------------
-- 用户
-- -----------------------------------------------------
drop table if exists user;
create table user
(
	id int auto_increment primary key comment '自增id',
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

insert into user(uname, passwd) values('admin', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');