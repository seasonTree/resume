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

    public function getUser($where){
        //获取用户名
        return User::where($where)->value('uname');
    }

    public function getOne($where){
        //获取一条数据
        return User::where($where)->find();
    }
    public function lst(){
        return $this->select()->toArray();
    }
    public function lstPage($pageIndex,$pageSize){
        if($pageIndex < 1){
            $pageIndex = 1;
        }
        $offset = ($pageIndex - 1) * $pageSize;
        $limitData = $this->limit($offset, $pageSize)->select();
        $count = User::count();
        $pageCount = ceil($count / $pageSize);
        $data = [
            'row' => $limitData,
            'total' => $pageCount
        ];
        return $data;
    }


}
