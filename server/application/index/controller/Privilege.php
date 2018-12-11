<?php
namespace app\index\controller;
use \think\Controller;
use think\Request;

class Privilege extends Controller
{

//	public function index (Request $request){
//
////        halt($request->priData);
//		return view('/user_privilege');
//
//	}
	//提供权限数据
//	public function lst(){
//        $model =model('Privilege');
//        $data = $model->getTree();
//        $count = count($data);
//        return json(['data'=>$data,'count'=>$count,'code'=>0,'msg'=>'权限列表数据']);
//    }
    public function edit(){
        $priModel = model('Privilege');
	    $data = input('post.data');
	    $data['pri_name']=preg_replace('/-+/','',$data['pri_name']);
	    unset($data['level']);
        $validate =validate('Privilege');
        if (!$validate->check($data)){
            $error =$validate->getError();
            return json(['data'=>'','code'=>1,'msg'=>$error]);
        }

        try{
            $id =$priModel->edit($data);

        }catch(\Exception $e){
            $error =$e->getMessage();
            return json(['data'=>'','code'=>1,'msg'=>$error]);

        }

	    return json(['data'=>'ok','code'=>0,'msg'=>'修改成功']);

    }
    public function add(){
	    $data = input('post.data');
	    echo '111';
	    halt($_POST);
        $validate =validate('Privilege');
        if (!$validate->check($data)){
            $error =$validate->getError();
             return json(['data'=>'','code'=>1,'msg'=>$error]);
        }
        try{
            $resData =model('Privilege')->add($data);
        }catch(\Exception $e){
            $error =$e->getMessage();
            return json(['message'=>$error]);
        }
        return json(['data'=>$resData,'code'=>0,'msg'=>'新增成功']);
    }
    public function del(){
	    $id = input('post.data');
        $res['msg'] = \app\index\model\Privilege::destroy($id);
        if ($res['msg']){
            return json(['data'=>'ok','code'=>0,'msg'=>'删除成功']);
        }else{
            return json(['data'=>'','code'=>1,'msg'=>'删除失败']);
        }

    }

    public function getSelect(){
        //获取选中
        $id = input('post.data');
        
    }

}
