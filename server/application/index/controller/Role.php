<?php
namespace app\index\controller;

class Role
{
//    protected $middleware =['Check'];
	public function index(){
       
		//角色主页
		return view('/user_role');
	}
	public function lst(){
        $list =model('Role')->lst();

        foreach ($list as $k=>$v){
            $v['username'] =array_unique(explode(',',$v['username']));$list[$k]['username'] =implode(',',$v['username']);
            $v['pri_name'] =array_unique(explode(',',$v['pri_name']));$list[$k]['pri_name'] =implode(',',$v['pri_name']);
            $v['pri_id'] =array_unique(explode(',',$v['pri_id']));$list[$k]['pri_id'] =implode(',',$v['pri_id']);
        }

        $priData =model('privilege')->getTree();

        $data = [
            'list' => $list,
            'role' => $priData
        ];

        return json(['data'=>$data,'code'=>0,'msg'=>'获取数据成功']);
    }
    public function add(){
	    $data = input('post.data');
	    if(empty($data['selected'])){
            return json(['data'=>'','code'=>1,'msg'=>'请选择权限']);
        }
	    $arr['role_name'] = $data['role_name'];
        foreach ($data['selected'] as $k =>$v){
            $arr['pri_id'][] =$v['id'];
        }
        $data = $arr;
        $validate =validate('Role');
        if (!$validate->check($data)){
            $error =$validate->getError();
            return json(['data'=>'','code'=>1,'msg'=>$error]);
        }

        $id=model('Role')->add($data);
        if($id){
            return json(['data'=>'','code'=>0,'msg'=>'新增成功']);
        }
    }
    public function edit(){
        $data =input('post.data');
        if(empty($data['selected'])){
            return json(['data'=>'','code'=>1,'msg'=>'请选择权限']);
        }
        $arr['role_name'] = $data['role_name'];
        $arr['id'] = $data['id'];
        foreach ($data['selected'] as $k =>$v){
            $arr['pri_id'][] =$v['id'];
        }
        $data = $arr;
        $validate =validate('Role');
        if (!$validate->check($data)){
            $error =$validate->getError();
            return json(['data'=>'','code'=>1,'msg'=>$error]);
        }
        $id=model('Role')->edit($data);
        if($id){
            return json(['data'=>'','code'=>0,'msg'=>'修改成功']);
        }
    }
    public function del(){
	    $data =input('post.data');
	    if(!empty($data['username'])){
            return json(['data'=>'','code'=>1,'msg'=>'请清空用户列表']);
        }
        if (model('role')->del($data['id'])){
            return json(['data'=>'','code'=>0,'msg'=>'删除成功']);
        }else{
            return json(['data'=>'','code'=>1,'msg'=>'删除失败']);
        }
    }
}
