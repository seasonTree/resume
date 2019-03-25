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
                          ->order('communicate_time desc')
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

    public function getCommByUname($where = '1=1'){
      //根据招聘人的用户名获取沟通信息
       return Communicate::alias('a')
                         ->join('rs_resume b','a.resume_id = b.id')
                         ->field('a.communicate_time,a.content,b.name')
                         ->where($where)
                         ->order('communicate_time desc')
                         ->select()
                         ->toArray();
    }

    public function getComm($where = '1=1'){
        //获取沟通信息，部分字段

        return Communicate::field('resume_id,id,ct_user,communicate_time')
                          ->where($where)
                          ->order('mfy_time desc')
                          ->select()
                          ->toArray();
    }
    public function getCommInfo($where = '1=1'){
        //获取沟通信息部分字段
      // echo $where;exit;
        return Communicate::alias('a')->join('rs_resume b','a.resume_id=b.id')->field('b.name,screen,arrange_interview,arrive,approved_interview,entry,a.ct_user,resume_id,communicate_time')->where($where)->select()->toArray();
      

    }

    public function getContent($where = '1=1'){
      //获取沟通内容
      return Communicate::field('content')->where($where)->select()->toArray();
    }



}
