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
        return Resume::field('id,name,phone,sex,age,work_year,email,expected_money,nearest_unit,nearest_job,english,expected_address,school,educational,speciality,mfy_time')->where($where)->select();
    }

    public function getOne($where = '1=1'){
        //获取一条数据
        return Resume::where($where)->find();
    }

    public function edit($data){
        //修改
        $temp = $this->getOne(['id' => $data['id']]);
        if ($temp['mfy_time'] != $data['mfy_time']) {
            return json(['msg' => '该数据已经更变，请刷新','code' => 10,'data' => []]);
        }
        unset($data['mfy_time']);
        return Resume::update($data);
    }

    public function del($where){
        return Resume::where($where)->delete();
    }

    public function getCount($where = '1=1'){
        return Resume::count();
    }


}
