<?php

namespace app\index\model;

use think\Db;
use think\Model;

class Role extends Model
{
    protected $table = 'rs_role';
    protected static function init()
    {
//        Role::event('after_insert',function ($Role){
//            $priIds=$Role->pri_id;
//            if (!empty($priIds)){
//                foreach($priIds as $k=>$v){
//                    $data[]=[
//                        'role_id'=>$Role->id,
//                        'pri_id'=>$v,
//                    ];
//                }
//                model("RolePri")->insertAll($data);
//            }
//
//        });
//        Role::event('after_update',function ($Role){
//
//            $priIds=$Role->pri_id;
//            if (!empty($priIds)){
//                foreach($priIds as $k=>$v){
//                    $data[]=[
//                        'role_id'=>$Role->id,
//                        'pri_id'=>$v,
//                    ];
//                }
//                model("RolePri")->where('role_id='.$Role->id)->delete();
//                model("RolePri")->insertAll($data);
//            }
//
//        });
//        Role::event('after_delete',function ($Role){
//            model("RolePri")->where('role_id='.$Role->id)->delete();
//        });
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
    public function lst(){
        $data=$this->alias('R')
//            ->field('R.id,R.role_name,group_concat(pri_name) pri_name,group_concat(pri_id) pri_id,group_concat(username) username')
//            ->join('user_role AR','R.id=AR.role_id','left')
//            ->join('user A','A.id=AR.user_id','left')
//            ->join('role_pri RP','R.id=RP.role_id','left')
//            ->join('privilege P','P.id=RP.pri_id','left')
//            ->group('R.id')
            ->select()->toArray();
        return $data;
    }
    public function getOne($id){
        $data=$this->alias('R')
//            ->field('R.id,R.role_name,pri_id')
//            ->join('role_pri RP','R.id=RP.role_id','left')
//            ->join('privilege.js P','P.id=RP.pri_id','left')
//            ->where('R.id='.$id)
            ->select()->toArray();
        return $data;
    }
    public function del($id){
       return $this->destroy($id);
    }

    public function getRoleId($where){
        //获取角色id
        return $this->where($where)->value('id');
    }
}
