<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
use app\index\model\Resume;
use app\index\model\Communicate;
use app\index\model\Client as ClientModel;
use think\Db;

class Client extends Controller
{	
	private $cli = '';
	public function __construct(){
		$this->cli = new ClientModel();
	}

    public function getList(){
    	//获取客户列表
    	$client_name = input('get.name');
    	$where = $client_name == ''?'1=1':"client_name='$client_name'";
    	$pageSize = input('get.pageSize');
        $pageIndex = input('get.pageIndex');
        $begin = ($pageIndex-1)*$pageSize;

    	$data = $this->cli->where($where)->limit($begin,$pageSize)->order('status asc,ct_time desc')->select();
    	$total = $this->cli->where($where)->count('id');
    	return json(['msg' => '获取成功','code' => 0,'data' => ['row' => $data,'total' => $total]]);

    }

    public function addClient(){
    	//添加客户
    	if (input('post.client_name') == '') {
    		return json(['msg' => '请输入客户名称','code' => 404]);
    	}
    	if($this->cli->getOne(['client_name' => input('post.client_name')])){
    		return json(['msg' => '该客户已存在','code' => 500]);
    	}
    	$id = $this->cli->add(['client_name' => input('post.client_name'),'ct_user' => Session::get('user_info')['uname']]);
    	$data = $this->cli->getOne(['id' => $id]);
    	return json(['msg' => '插入成功','code' => 0,'data' => $data]);
    }

    public function delClient(){
    	//删除客户
    	if (input('post.id') == '') {
    		return json(['msg' => '没有找到id','code' => 404]);
    	}
    	$res = $this->cli->del(['id' => input('post.id')]);
    	if ($res) {
    		return json(['msg' => '删除成功','code' => 0]);
    	}
    	return json(['msg' => '删除失败','code' => 500]);

    }

    public function editClient(){
    	//修改客户
    	$data = input('post.');
    	if (!$this->cli->getOne($data)) {
    		return json(['msg' => '数据已发生变化，请刷新','code' => 500]);
    	}
    	unset($data['mfy_time']);//删除修改时间，让数据库自动写入
    	$data['mfy_user'] = Session::get('user_info')['uname'];//写入修改人
    	$res = $this->cli->edit($data);
    	if ($res) {
    		return json(['msg' => '修改成功','code' => 0]);
    	}
    	return json(['msg' => '修改失败','code' => 500]);
    }

    public function getCliById(){
    	//获取单个客户信息
    	if (input('post.id') == '') {
    		return json(['msg' => '没有找到id','code' => 404]);
    	}
    	$data = $this->cli->getOne(['id' => input('post.id')]);
    	return json(['msg' => '获取成功','code' => 0,'data' => $data]);
    }

    public function changeStatus(){
    	//改变状态
    	$res = $this->cli->edit(input('post.'));
    	if ($res) {
    		return json(['msg' => '修改成功','code' => 0]);
    	}
    	return json(['msg' => '修改失败','code' => 500]);
    }

    public function getAll(){
    	//获取所有客户
    	$list = $this->cli->get(['status' => 0]);
    	return json(['msg' => '获取成功','code' => 0,'data' => $list]);
    }

    
}
