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
        return Communicate::alias('a')
                          ->join('rs_user b','a.ct_user = b.uname')
                          ->field('a.*,b.personal_name')
                          ->where($where)
                          ->select();
    }

    public function getOne($where = '1=1'){
        //获取一条数据
        return Communicate::alias('a')
                          ->join('rs_user b','a.ct_user = b.uname')
                          ->field('a.*,b.personal_name')
                          ->where($where)
                          ->find();
    }

    public function del($where){
        //删除
        return Communicate::where($where)->delete();
    }

    public function getCount($where = '1=1'){
        //获取条数
        return Communicate::where($where)->count();
    }

    public function edit($data){
        //修改
        $temp = $this->getOne(['a.id' => $data['id']]);
        if ($temp['mfy_time'] != $data['mfy_time']) {
            return false;
        }
        unset($data['mfy_time']);
        return Communicate::update($data);
    }

    public function getComm($where = '1=1'){
        //获取沟通信息，部分字段

        return Communicate::alias('a')
                          ->join('rs_user b','a.ct_user = b.uname')
                          ->field('a.resume_id,a.id,a.ct_user,b.personal_name,a.communicate_time')
                          ->where($where)
                          ->select();
    }
    public function getCommInfo($where = '1=1'){
        //获取沟通信息部分字段
      // echo $where;exit;
        return Communicate::field('screen,arrange_interview,arrive,approved_interview,entry,ct_user,resume_id,communicate_time,content')
                          ->where($where)
                          ->select()
                          ->toArray();
      

    }



}
