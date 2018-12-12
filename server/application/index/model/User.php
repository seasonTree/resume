<?php

namespace app\index\model;

use think\Db;
use think\Model;

class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'rs_user';
    // 主键
    protected $pk = 'id';

    public function checkUser($where){
        //用户名
        return $this->getOne($where);
    }

    public function getOne($where){
        //获取一条数据
        return User::where($where)->find();
    }



}
