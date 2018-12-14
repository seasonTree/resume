<?php
namespace app\index\model;

use think\Model;

class Privilege extends Model
{
    protected $table = 'rs_permission';
    public $data ='';
    protected static function init()
    {
        Privilege::event('after_delete',function ($Privilege){
            $nums=$Privilege->getChildren($Privilege->id);
                Privilege::destroy($nums);
        });
    }

    public function add($data){
        if (!is_array($data)){
            return exception('传递数据不合法');
        }
        $this->allowField(true)->save($data);

        return $this->getOne($this->id);
    }
    public function edit($data){
        if (!is_array($data)){
            return exception('传递数据不合法');
        }
        $num =$this->setField($data);
        return $num;
    }
    public function del($id){
       $chlids = $this->getChildren($id);
       if(empty($chlids)){
            return $res =Privilege::destroy($id);
       }else{
           return $res = false;
       }
    }
    public function getOne($id){
        $data =Privilege::where(['id'=>$id])->find();
        $this->data = $data;
        $res = [];
        while($data['parent_id'] != 0){
            array_push($res,$data['parent_id']);
            $data =Privilege::where(['id'=>$data['parent_id']])->find();
        }
      $result = $this->data;
      $result['top_class'] = $res;
      return $result;
    }
    /**
     * 获取权限列表数据
    */
    public function getTree(){
        $data =$this->select()->toArray();
        $data = $this->_reSort($data);
        $data =$this->getTrees($data);
//        halt($data);

        return $data;
    }
    /**
     *获取树结构
     */
    public function getTrees($list,$pk='id',$pid='parent_id',$child='children',$root=0){
        $tree=array();
        foreach($list as $key=> $val){

            if($val[$pid]==$root){
                //获取当前$pid所有子类
                unset($list[$key]);
                if(! empty($list)){
                    $child=getTree($list,$pk,$pid,$child,$val[$pk]);
                    if(!empty($child)){
                        $val['children']=$child;
                    }
                }
                $tree[]=$val;
            }
        }
        return $tree;
    }
    private function _reSort($data,$parent_id=0,$level=0,$isClear=TRUE)
    {
        static $ret =array();
        if ($isClear)
            $ret =array();
        foreach ($data as $k =>$v){
            if ($v['parent_id'] == $parent_id){
                $v['level']=$level;
                $ret[]=$v;
                $this->_reSort($data,$v['id'],$level+1,$isClear=FALSE);
            }
        }
        return $ret;
    }
    public function getChildren($id){
        $data=$this->select();
        return $this->_children($data,$id);
    }
    private function _children($data,$parent_id=0,$isClear=TRUE){
        static $ret =array();
        if ($isClear)
            $ret =array();
        foreach ($data as $k =>$v){
            if ($v['parent_id'] ==$parent_id){
                $ret[]=$v['id'];
                $this->_children($data,$v['id'],FALSE);
            }
        }
        return $ret;
    }
}