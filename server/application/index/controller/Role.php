<?php
namespace app\index\controller;

use app\index\model\RolePri;
use app\index\model\UserRole;

class Role
{
    public $data = '';
//    protected $middleware =['Check'];
//	public function index(){
//
//		//角色主页
//		return view('/user_role');
//	}
    public function getOne(){
        $id = input('post.id');
        $data =\app\index\model\Role::get($id);
        return json(['data'=>$data,'code'=>0,'msg'=>'获取数据成功']);
    }
    public function getRolePri(){
        $id = input('get.id');
        $data =model('RolePri')->where("role_id",$id)->select()->toArray();
        $data =array_column($data,'p_id');
        return json(['data'=>$data,'code'=>0,'msg'=>'获取数据成功']);
    }
    public function setRolePri(){
        $data = input('post.');
        $this->data = $data['id'];
        $arr = [];
        try{
            RolePri::where(['role_id'=>$data['id']])->delete();
            foreach ($data['permission'] as $k =>$v){
                array_push($arr,['role_id'=>$data['id'],'p_id'=>$v]);
            }
            model('RolePri')->saveAll($arr);
        }catch(\Exception $e){
            return json(['data'=>$e->getMessage(),'code'=>1,'msg'=>'设置失败']);
        }

        return json(['data'=>null,'code'=>0,'msg'=>'设置成功']);


    }
	public function lst(){
	    $data = input('get.');

        $list =model('Role')->lst();

//        foreach ($list as $k=>$v){
//            $v['username'] =array_unique(explode(',',$v['username']));$list[$k]['username'] =implode(',',$v['username']);
//            $v['pri_name'] =array_unique(explode(',',$v['pri_name']));$list[$k]['pri_name'] =implode(',',$v['pri_name']);
//            $v['pri_id'] =array_unique(explode(',',$v['pri_id']));$list[$k]['pri_id'] =implode(',',$v['pri_id']);
//        }

        $priData =model('privilege')->getTree();

        $data = [
            'list' => $list,
            'role' => $priData
        ];

        return json(['data'=>$list,'code'=>0,'msg'=>'获取数据成功']);
    }
    public function add(){
	    $data = input('post.');

//	    if(empty($data['selected'])){
//            return json(['data'=>'','code'=>1,'msg'=>'请选择权限']);
//        }
//	    $arr['role_name'] = $data['role_name'];
//        foreach ($data['selected'] as $k =>$v){
//            $arr['pri_id'][] =$v['id'];
//        }
//        $data = $arr;
//        $validate =validate('Role');
//        if (!$validate->check($data)){
//            $error =$validate->getError();
//            return json(['data'=>'','code'=>1,'msg'=>$error]);
//        }

        $id=model('Role')->add($data);
        if($id){
            $data = model('Role')->getOne($id);
            return json(['data'=>$data[0],'code'=>0,'msg'=>'新增成功']);
        }
    }
    public function getUserById(){
        $data = input('get.');
        $data = model('UserRole')->alias('UR')
//            ->join('user U','U.id=UR.user_id','left')
            ->where('role_id',$data['role_id'])->select()->toArray();
        $data =array_column($data,'user_id');
        return json(['data'=>$data,'code'=>0,'msg'=>'获取数据成功']);
    }
    public function setRoleUser(){
        $data = input('post.');
        $arr = [];
        try{
            UserRole::where(['role_id'=>$data['role_id']])->delete();
            foreach ($data['role_user'] as $k =>$v){
                array_push($arr,['role_id'=>$data['role_id'],'user_id'=>$v]);
            }
            model('UserRole')->saveAll($arr);
        }catch(\Exception $e){
            return json(['data'=>$e->getMessage(),'code'=>1,'msg'=>'设置失败']);
        }

        return json(['data'=>null,'code'=>0,'msg'=>'设置成功']);
    }
    public function edit(){
        $data =input('post.');

//        if(empty($data['selected'])){
//            return json(['data'=>'','code'=>1,'msg'=>'请选择权限']);
//        }
//        $arr['role_name'] = $data['role_name'];
//        $arr['id'] = $data['id'];
//        foreach ($data['selected'] as $k =>$v){
//            $arr['pri_id'][] =$v['id'];
//        }
//        $data = $arr;
//        $validate =validate('Role');
//        if (!$validate->check($data)){
//            $error =$validate->getError();
//            return json(['data'=>'','code'=>1,'msg'=>$error]);
//        }
        $id=model('Role')->edit($data);
        if($id){
            $data =\app\index\model\Role::get($id);
            return json(['data'=>$data,'code'=>0,'msg'=>'修改成功']);
        }
    }
    public function del(){
	    $id =input('post.id');
	    $data = model('UserRole')->where('role_id',$id)->select()->toArray();
	    if(!empty($data)){
            return json(['data'=>'','code'=>1,'msg'=>'请清空用户列表']);
        }
        try{
            model('role')->del($id);
            RolePri::where(['role_id'=>$id])->delete();

        }catch (\Exception $e){
            return json(['data'=>'','code'=>1,'msg'=>'删除失败']);
        }
        return json(['data'=>null,'code'=>0,'msg'=>'删除成功']);

    }
}
