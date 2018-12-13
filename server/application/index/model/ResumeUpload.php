<?php

namespace app\index\model;

use think\Db;
use think\Model;

class ResumeUpload extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'rs_resume_upload';
    // 主键
    protected $pk = 'id';

    public function add($data){
        //添加
        return ResumeUpload::insertGetId($data);
    }

    public function get($where = '1=1'){
        //获取数据
        return ResumeUpload::where($where)->select();
    }


}
