<?php

namespace app\index\model;

use think\Model;

class Client extends Model
{
    protected $table = 'rs_client';
    // 主键
    protected $pk = 'id';

    public function get($where = '1=1',$order = 'id desc'){
    	//获取
    	return Client::where($where)->order($order)->select();
    }

    public function getOne($where = '1=1'){
    	//获取一条
    	return Client::where($where)->find();
    }

    public function add($data){
    	//添加一条数据并返回id
    	return Client::insertGetId($data);
    }

    public function del($where){
    	//删除数据
    	return Client::where($where)->delete();
    }

    public function edit($data){
    	//修改
    	return Client::update($data);
    }
}
