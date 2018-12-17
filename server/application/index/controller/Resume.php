<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
use app\index\model\ResumeUpload;
use app\index\model\Resume as ResumeModel;
use app\index\model\User;
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/Analysis.php';
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/phpanalysis/phpanalysis.class.php';

class Resume extends Controller
{	

    public function index()
    {
        return view('/index');
    }

    public function basicData($parm){
        //基本资料
        // dump($parm);exit;
        $parm = implode("\n",$parm);
        $rule = config('config.resume_rule');
        $base_rule = config('config.base_rule');
        $base_replace_blank = config('config.base_replace_blank');
        foreach ($base_rule as $string) {
            //处理特定字符格式
            $parm = preg_replace("/$string/","\n".$string,$parm);
        }
        $parm = explode("\n",$parm);
        $arr = [];
        $rule = config('config.basicData');
        unset($parm[0]);
        foreach ($parm as $k => $v) {
            $v = $this->trimall($v);
            $v = preg_replace($base_replace_blank,'',$v);
            // $v = phpanalysis($v);
            // dump($v);
            foreach ($rule as $n => $pattern) {
                if (preg_match($pattern,$v,$preg)) {

                    if ($n == 'name') {
                        // dump($v);
                        $len = strlen($v);
                        if ($len >15) {
                            continue;
                        }
                    }

                    $strpos = strpos($v,$preg[0]);
                    $strlen = strlen($preg[0]);

                    for ($i = 0;$i < $strlen; $i++) {
                        $v[$strpos] = '';
                        $strpos++;
                    }
                    // dump($preg[0]);

                    if ($n == 'birthday') {
                        $year = time() - strtotime($preg[0]);
                        if($year < 567648000){
                            continue;
                        }
                    }
                    unset($rule[$n]);
                    $temp_str = preg_replace("/:|：/",'=',$preg[0]);
                    if (isset(explode('=', $temp_str)[1])) {
                        $arr[$n] = explode('=',$temp_str)[1];
                    }
                    else{
                        $arr[$n] = $preg[0];
                    }
                    
                            
                    // dump($rule);
                }
                
            }
            
        }
        // dump($rule);
        // dump($arr);exit;
        // exit;
        return $arr;
    }

    public function workExperience($parm){
        //工作经历
        $str = '';
        foreach ($parm as $k => $v) {
            if ($v == '') {
                continue;
            }
            $str.= $v."\n";
        }

        return $str;
    }


    public function educationalBackground($parm){
        //教育背景
        $arr = [];
        $rule = config('config.educationalBackground');
        foreach ($parm as $k => $v) {
            $v = $this->trimall($v);
            // $v = phpanalysis($v);
            // dump($v);
            foreach ($rule as $n => $pattern) {

                if (preg_match($pattern,$v,$preg)) {
                    $strpos = strpos($v,$preg[0]);
                    $strlen = strlen($preg[0]);
                    for ($i = 0;$i < $strlen; $i++) {
                        $v[$strpos] = '';
                        $strpos++;
                    }
                    
                    unset($rule[$n]);
                    $arr[$n] = $preg[0];
                    // dump($rule);
                }
                
            }
            
        }
        return $arr;
    }

    public function projectExperience($parm){
        //项目经验
        $str = '';
        foreach ($parm as $k => $v) {
            if ($v == '') {
                continue;
            }
            $str.= $v."\n";
        }
        return $str;
    }

    public function selfEvaluation($parm){
        //自我评价
        $str = '';
        foreach ($parm as $k => $v) {
            if ($v == '') {
                continue;
            }
            $str.= $v."\n";
        }
        return $str;
    }

    public function skillExpertise($parm){
        //技能专长
        $str = '';
        foreach ($parm as $k => $v) {
            if ($v == '') {
                continue;
            }
            $str.= $v."\n";
        }
        return $str;
    }

    public function getResumeData(){
        //获取简历内容
        $content = input('content');
        if ($content == '') {
            return json(['code' => 1,'msg' => '缺少内容','data' => []]);
        }
        $rule = config('config.resume_rule');
        foreach ($rule as $k => $v) {
            //处理特定字符格式,加换行
            $content = preg_replace("/$v/","\n".$v,$content);
        }
        
        $content = explode("\n",$content);

        array_unshift($content,'基本资料');
        // dump($content);exit;
        $begin = 0;
        $rule = config('config.group_title');
        $arr = [];//分类集合
        $lt_index = count($content) - 1;
        $resume_title = config('config.resume_title');
        foreach ($content as $k => $v) {
            // $v = preg_replace("# #",'',$v);//去空格
            $v = $this->trimall($v);
            // dump($v);
            if(preg_match($rule,$v,$preg) || $k == $lt_index){
                if ($k != 0) {
                    $length = $k - $begin;

                    if ($k == $lt_index) {
                        $length = $length + 1;
                    }
                    
                    if (!empty($arr[$resume_title[$field]])) {
                        $temp = array_slice($content,$begin,$length);
                        foreach ($temp as $a => $b) {
                            array_push($arr[$resume_title[$field]],$b);
                        } 
                    }
                    else{
                        $arr[$resume_title[$field]]= array_slice($content,$begin,$length);
                    }

                    $begin = $k;
                }

                if ($k != $lt_index) {
                    $field = $preg[0];
                }
                
            }

        }
        $list = [];
        foreach ($arr as $method => $parm) {
            // $list[$method] = $this->$method($parm);
            $temp = $this->$method($parm);
            if (is_array($temp)) {
                $list = array_merge($list,$temp);
            }
            else{
                $list[$method] = $temp;
            }
            
        }

        return json(['code' => 0,'msg' => '解析成功','data' => $list]);

    }

    public function trimall($str){
        $qian = array(" ","　","\t","\n","\r");
        return str_replace($qian, '', $str);  
    }

    public function upload(){
        //上传
        $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/';
        // 获取表单上传文件
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'doc,docx,html,htm,mht'])->move($path);
        if($info){
            $upload = new ResumeUpload();
            $file_name = preg_replace("/\s/",'_',$_FILES['file']['name']);
            $find = $upload->getOne(['file_name' => $file_name]);
            if ($find) {
                return json(['msg' => '文件已经存在','code' => 3]);
            }
            $data = array(
                'resume_url' => $path.$info->getSaveName(),
                'ct_user'  => Session::get('user_info')['uname'],
                'file_name'  => $file_name,
                'resume_id' => input('id')//预留的简历id
                );
            //入库操作
            $data['id'] = $upload->add($data);
            $data['ct_time'] = date('Y-m-d H:i:s',time());
            
            return json(['msg' => '上传成功','code' => 0,'data' => $data ]);
        }else{
            // 上传失败获取错误信息
            return json(['msg' => $file->getError(),'code' => 1]);
        }
    }

    public function uploadList(){
        //上传列表
        $resume_id = input('id');
        $upload = new ResumeUpload();
        $data = $upload->get(['resume_id' => $resume_id]);
        if ($data) {
            return json(['msg' => '获取成功','code' => 0,'data' => $data]);
        }
        else{
            return json(['msg' => '获取失败','code' => 1]);
        }

    }

    public function getResumeList(){
        //简历列表
        $resume = new ResumeModel();
        $data = $resume->get();
        if ($data) {
            return json(['msg' => '获取成功','code' => 0,'data' => $data]);
        }
        else{
            return json(['msg' => '无数据','code' => 1]);
        }
    }

    public function getResumeOne(){
        //获取简历内容
        $id = input('id');
        if (empty($id)) {
            return json(['msg' => 'id字段不存在','code' => 2]);
        }
        $resume = new ResumeModel();
        $data = $resume->getOne(['id' => $id]);
        if ($data) {
            return json(['msg' => '获取成功','code' => 0,'data' => $data]);
        }
        else{
            return json(['msg' => '获取失败','code' => 1]);
        }
    }

    public function addResume(){
        //添加简历
        $data = input('post.');
        if (empty($data)) {
            return json(['msg' => '没有数据','code' => 2]);
        }
        $resume = new ResumeModel();
        $id = $resume->add($data);
        $data = $resume->getOne(['id' => $id]);
        if ($data) {
            return json(['msg' => '添加成功','code' => 0,'data' => $data]);
        }
        else{
            return json(['msg' => '添加失败','code' => 1]);
        }
    }

    public function editResume(){
        //修改简历
        $data = input('post.');
        if (empty($data)) {
            return json(['msg' => '没有数据','code' => 2]);
        }
        $resume = new ResumeModel();
        $res = $resume->edit($data);
        $data = $resume->getOne(['id' => $data['id']]);
        if ($data) {
            return json(['msg' => '修改成功','code' => 0,'data' => $data]);
        }
        else{
            return json(['msg' => '修改失败','code' => 1]);
        }
    }
    
    public function delResume(){
        //删除简历
        $id = input('id');
        if (empty($id)) {
            return json(['msg' => 'id字段不存在','code' => 2]);
        }
        $resume = new ResumeModel();
        $res = $resume->del(['id' => $id]);
        $upload = new ResumeUpload();
        $data = $upload->get(['resume_id' => $id]);
        foreach ($data as $k => $v) {
            $upload->del(['id' => $v['id']]);
            @unlink($v['resume_url']);
        }
        if ($res) {
            return json(['msg' => '删除成功','code' => 0]);
        }
        else{
            return json(['msg' => '删除失败','code' => 1]);
        }
    }

    public function delFile(){
        //删除简历文件
        $id = input('id');
        if (empty($id)) {
            return json(['msg' => 'id字段不存在','code' => 2]);
        }
        $upload = new ResumeUpload();
        $res = $upload->del(['id' => $id]);
        $path = input('resume_rul');
        if (empty($path)) {
            return json(['msg' => '文件路径不存在','code' => 3]);
        }
        @unlink($path);
        if ($res) {
            return json(['msg' => '删除成功','code' => 0]);
        }
        else{
            return json(['msg' => '删除失败','code' => 1]);
        }
    }


}
