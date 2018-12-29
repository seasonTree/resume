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

    public function getUserName($where){
        //获取真名
        return User::where($where)->value('personal_name');
    }

    public function getOne($where){
        //获取一条数据
        return User::where($where)->find();
    }

    public function getUserInfo($where = '1=1'){
        //获取部分字段
        return User::field('uname,personal_name,ct_user')->where($where)->select()->toArray();
    }
    public function lst(){
        return $this->field('id,uname,personal_name,phone,ct_user,ct_time,status,mfy_time')->select()->toArray();
    }
    public function updateToken($data){
        return User::where(['id' => $data['id']])->update(['token' => $data['token']]);
    }
    public function lstPage($pageIndex,$pageSize, $where = []){

        if($pageIndex < 1){
            $pageIndex = 1;
        }
        $offset = ($pageIndex - 1) * $pageSize;

        $limitData = $this->field('id,uname,personal_name,phone,ct_user,ct_time,status,mfy_time')->limit($offset, $pageSize)->where($where)->select();
        $count = $this->where($where)->count();
        // $pageCount = ceil($count / $pageSize);
        $data = [
            'row' => $limitData,
            'total' => $count
        ];
        return $data;
    }
    public function add($data){
        $this->allowField(true)->save($data);
        return $this->id;
    }
    public function edit($data){
//        halt($data);
        if (!is_array($data)){
            return exception('传递数据不合法');
        }
        $num =$this->allowField(true)->save($data,['id'=>$data['id']]);
        return $num;
    }
    public function getPriById($id){
        if($id == 1){
            $data =model('Privilege')->select()->toArray();
        }else{
            $data =$this->alias('U')->field('P.*')
            ->join('user_role UR','UR.user_id = U.id')
            ->join('role R','UR.role_id = R.id')
            ->join('user_permission UP','UP.role_id = R.id')
            ->join('permission P','UP.p_id = P.id')
            ->where(['U.id'=>$id])->order('P.idx asc, P.id asc')->select()->toArray();
        }

        return $data;
    }


}
