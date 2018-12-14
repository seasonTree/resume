<?php

namespace app\index\model;

use think\Db;
use think\Model;

class Communicate extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'rs_communicate';
    // 主键
    protected $pk = 'id';

    public function add($data){
        //添加
        return Communicate::insertGetId($data);
    }

    public function get($where = '1=1'){
        //获取数据
        return Communicate::where($where)->select();
    }

    public function getOne($where = '1=1'){
        //获取一条数据
        return Communicate::where($where)->find();
    }

    public function del($where){
        //删除
        return Communicate::where($where)->delete();
    }


}
