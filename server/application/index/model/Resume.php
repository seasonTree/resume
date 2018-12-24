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
        return Resume::field('id,name,phone,sex,age,work_year,email,expected_money,nearest_unit,nearest_job,english,expected_address,school,educational,speciality,mfy_time')->where($where)->order('mfy_time desc')->select();
    }
    public function addAll($data){
        //添加全部
        return Resume::insertAll($data);
    }
    public function getOne($where = '1=1'){
        //获取一条数据
        return Resume::where($where)->find();
    }

    public function edit($data){
        //修改
        $temp = $this->getOne(['id' => $data['id']]);
        if ($temp['mfy_time'] != $data['mfy_time']) {
            return false;
        }
        unset($data['mfy_time']);
        return Resume::update($data);
    }

    public function del($where){
        return Resume::where($where)->delete();
    }

    public function getCount($where = '1=1'){
        return Resume::where($where)->count();
    }

    public function getCandidate($where = '1=1'){
        //获取候选人
        return Resume::where($where)
                     ->field('id,name,phone,email,school,educational,graduation_time,work_year,source,ct_user')
                     ->select()
                     ->toArray();
    }

    public function getUname($where){
        //获取候选人名字
        return Resume::where($where)->value('name'); 
        
    }




}
