<?php

namespace app\index\model;

use think\Db;
use think\Model;

class Resume extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'rs_resume';
    // 主键
    protected $pk = 'id';

    public function add($data){
        //添加
        return Resume::insertGetId($data);
    }

    public function get($where = '1=1'){
        //获取数据
        return Resume::where($where)->select();
    }

    public function getOne($where = '1=1'){
        //获取一条数据
        return Resume::where($where)->find();
    }

    public function edit($data){
        //修改
        return Resume::update($data);
    }

    public function del($where){
        return Resume::where($where)->delete();
    }


}