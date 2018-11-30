create database if not exists resume default charset utf8 collate utf8_unicode_ci;

use resume;

-- -----------------------------------------------------
-- 用户
-- -----------------------------------------------------
drop table if exists user;
create table user
(
	id int auto_increment primary key,
	uname varchar(64) not null,
	passwd varchar(128) not null,
    pesonal_name varchar(32) not null default '', /* 姓名 */
    status tinyint(1) not null default 0, /* 0: 正常， 1: 禁用 */
	ct_user varchar(64) default '' null,
	ct_time datetime default CURRENT_TIMESTAMP not null ,
	mfy_user varchar(64) default '' null,
	mfy_time datetime default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP not null,
	unique index `user_uname` (`uname`)
) engine=InnoDB;

insert into user(uname, passwd) values('admin', '7c4a8d09ca3762af61e59520943dc26494f8941b');