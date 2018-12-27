<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
use app\index\model\ResumeUpload;
use app\index\model\Resume as ResumeModel;
use app\index\model\User;
use PHPExcel_IOFactory;
use PHPExcel;
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/Analysis.php';
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/phpanalysis/phpanalysis.class.php';

class Resume extends Controller
{	
    public function checkName(){
        $name = input('get.name');
        $data = model('resume')->get(['name'=>$name]);
        return json(['code' => 0,'msg' => 'ok','data' => $data]);
    }
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

    public function importResume(){
        //导入简历
        $data = input('post.data');
        $personal_name = array_column($data,'personal_name');
        $phone = array_column($data,'phone');
        $check_data = '';
        $resume = new ResumeModel();
        // foreach ($phone as $a => $b) {
        //     $name = $resume->getUname(['phone' => $b]);
        //     if ($name) {
        //         $check_data.='姓名:'.$name.',电话:'.$b.'已存在';
        //     }
        // }
        // if ($check_data != '') {
        //     return json(['msg' => $check_data,'code' => 3,'data' => []]);
        // }
        // $user = new User();
        // $check = [];
        // foreach ($personal_name as $k => $v) {
        //     $temp = $data[$k]['name'].$data[$k]['phone'];
        //     if (in_array($temp,$check)) {
        //         return json(['msg' => '检测到重复数据，请检查','code' => 2,'data' => []]);
        //     }
        //     $ct_user = $user->getUser(['personal_name' => $v]);
        //     if ($ct_user == '') {
        //         $data[$k]['ct_user'] = Session::get('user_info')['uname'];
        //     }
        //     else{
        //         $data[$k]['ct_user'] = $ct_user;
        //     }
        //     unset($data[$k]['personal_name']);


        // }

        $user = new User();
        $check = [];

        foreach ($data as $k => $v) {
            $name = $resume->getUname(['phone' => $v['phone']]);
            if ($name) {
                $check_data.='姓名:'.$name.',电话:'.$v['phone'].'已存在';
            }

            $temp = $data[$k]['name'].$data[$k]['phone'];
            if (in_array($temp,$check)) {
                return json(['msg' => '检测到重复数据，请检查','code' => 2,'data' => []]);
            }
            $ct_user = $user->getUser(['personal_name' => $v['personal_name']]);
            if ($ct_user == '') {
                $data[$k]['ct_user'] = Session::get('user_info')['uname'];
            }
            else{
                $data[$k]['ct_user'] = $ct_user;
            }
            unset($data[$k]['personal_name']);

            if(isset($v['work_year'])){
                $data[$k]['work_year'] = (int)$data[$k]['work_year'];
            }
            if (isset($v['age'])) {
                $data[$k]['age'] = (int)$data[$k]['age'];
            }

            if (isset($v['expected_money'])) {
                $money = preg_replace("/(到|至)/",'-',$v['expected_money']);
                $money = explode('-',$v['expected_money']);

                if (count($money) > 1) {
                    $data[$k]['expected_money_start'] = (int)$money[0];
                    $data[$k]['expected_money_end'] = (int)$money[1];
                }
                else{
                    $data[$k]['expected_money_start'] = (int)$money[0];
                    $data[$k]['expected_money_end'] = (int)$money[0];
                }
            }

        }

        if ($check_data != '') {
            return json(['msg' => $check_data,'code' => 3,'data' => []]);
        }
        
        // dump($data);exit;

        $res = $resume->addAll($data);

        if ($res) {
            return json(['msg' => '添加成功','code' => 0,'data' => []]);
        }
        else{
            return json(['msg' => '添加失败','code' => 1,'data' => []]);
        }

    }

    public function uploadResume(){
        //导入简历

        $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/';
        // 获取表单上传文件
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'xlsx,xls'])->move($path);
        if($info){
            $file = $path.$info->getSaveName();//获取路径
            $data = $this->readResume($file);
            if (empty($data)) {
                return json(['msg' => '没有数据','code' => 2]);
            }
            unset($info);//一定要unset之后才能进行删除操作，否则请求会被拒绝
            @unlink($file);
            return json(['msg' => '上传成功','code' => 0,'data' => array_merge($data) ]);
        }else{
            // 上传失败获取错误信息
            return json(['msg' => $file->getError(),'code' => 1]);
        }
    }

    public function readResume($file){
        //解析简历
        // $file = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/test.xlsx';
        if( pathinfo($file)['extension'] =='xlsx' )
        {
          $obj = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        else
        {
          $obj = \PHPExcel_IOFactory::createReader('Excel5');
        }

        //实例化
        $obj_excel = $obj->load($file,$encode = 'utf-8');
        $sheet = $obj_excel -> getSheet(0);
        $total = $sheet -> getHighestRow(); // 取得总行数
        $data = [];
        $temp_time = '';
        for($i = 2;$i <= $total;$i++){
            if (empty($obj_excel -> getActiveSheet() -> getCell("B".$i)->getValue())) {
                continue;
            }
            $data[$i]['personal_name'] = $obj_excel -> getActiveSheet() -> getCell("A".$i)->getValue();
            $temp_time = $obj_excel -> getActiveSheet() -> getCell("B".$i)->getValue();

            if ($temp_time != '') {
                $time = ($temp_time-25569)*24*60*60; //获得秒数
                $data[$i]['ct_time'] = date('Y-m-d', $time);   //转化时间
            }
            else{
                $data[$i]['ct_time'] = '';
            }
            
            $data[$i]['expected_job'] = $obj_excel -> getActiveSheet() -> getCell("C".$i)->getValue();
            $data[$i]['name'] = $obj_excel -> getActiveSheet() -> getCell("D".$i)->getValue();
            $data[$i]['phone'] = $obj_excel -> getActiveSheet() -> getCell("E".$i)->getValue();
            $data[$i]['email'] = $obj_excel -> getActiveSheet() -> getCell("F".$i)->getValue();
            $data[$i]['school'] = $obj_excel -> getActiveSheet() -> getCell("G".$i)->getValue();
            $data[$i]['educational'] = $obj_excel -> getActiveSheet() -> getCell("H".$i)->getValue();
            $data[$i]['graduation_time'] = $obj_excel -> getActiveSheet() -> getCell("I".$i)->getValue();
            $data[$i]['work_year'] = $obj_excel -> getActiveSheet() -> getCell("J".$i)->getValue();
            $data[$i]['source'] = $obj_excel -> getActiveSheet() -> getCell("K".$i)->getValue();
            $data[$i]['custom1'] = $obj_excel -> getActiveSheet() -> getCell("M".$i)->getValue();
            $data[$i]['custom2'] = $obj_excel -> getActiveSheet() -> getCell("N".$i)->getValue();
            $data[$i]['custom3'] = $obj_excel -> getActiveSheet() -> getCell("L".$i)->getValue();

        }
        return $data;

    }

    public function upload(){
        //上传
        $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/';
        // 获取表单上传文件
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>2097152,'ext'=>'xls,xlsx,doc,docx,html,htm,mht'])->move($path);
        if($info){
            $upload = new ResumeUpload();
            $file_name = preg_replace("/\s/",'_',$_FILES['file']['name']);
            $find = $upload->getOne(['file_name' => $file_name,'resume_id' => input('resume_id')]);
            if ($find) {
                return json(['msg' => '文件已经存在','code' => 3]);
            }
            $data = array(
                'resume_url' => $path.$info->getSaveName(),
                'ct_user'  => Session::get('user_info')['uname'],
                'file_name'  => $file_name,
                'resume_id' => input('resume_id')//预留的简历id
                );
            //入库操作
            $data['id'] = $upload->add($data);
            $data['ct_time'] = date('Y-m-d H:i:s',time());
            $user = new User();
            $data['personal_name'] = Session::get('user_info')['personal_name'];
            
            return json(['msg' => '上传成功','code' => 0,'data' => $data]);
        }else{
            // 上传失败获取错误信息
            return json(['msg' => $file->getError(),'code' => 1]);
        }
    }

    public function uploadList(){
        //上传列表

        $resume_id = input('resume_id');
        $upload = new ResumeUpload();
        $user = new User();
        $data = $upload->get(['resume_id' => $resume_id])->toArray();

        foreach ($data as $k => $v) {
            $data[$k]['personal_name'] = $user->getUserName(['uname' => $v['ct_user']]);
        }
        return json(['msg' => '获取成功','code' => 0,'data' => $data]);
        


    }

    public function getResumeList(){
        //简历列表
        $input = input('get.');
        $data = '';
        if (count($input) > 3 ) {
            $data = $this->search($input);
        }
        else{
            $resume = new ResumeModel();
            $data = $resume->get();
            $count = $resume->getCount();
        }
        
        if ($data) {
            return json(['msg' => '获取成功','code' => 0,'data' => [ 'row' => $data,'total' => $count]]);
        }
        else{
            return json(['msg' => '无数据','code' => 1]);
        }
    }

    public function getResumeOne(){
        //获取简历内容
        $id = input('id');
   
        if ($id =='') {
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

        $data['ct_user'] = Session::get('user_info')['uname'];

        if(isset($data['work_year'])){
            $data['work_year'] = (int)$data['work_year'];
        }
        if (isset($data['age'])) {
            $data['age'] = (int)$data['age'];
        }

        if (isset($data['expected_money'])) {
            $money = preg_replace("/(到|至)/",'-',$data['expected_money']);
            $money = explode('-',$data['expected_money']);

            if (count($money) > 1) {
                $data['expected_money_start'] = (int)$money[0];
                $data['expected_money_end'] = (int)$money[1];
            }
            else{
                $data['expected_money_start'] = (int)$money[0];
                $data['expected_money_end'] = (int)$money[0];
            }
        }

        $resume = new ResumeModel();
        $id = $resume->add($data);
        $data['id'] = $id;
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
        $data['mfy_user'] = Session::get('user_info')['uname'];
        if (empty($data)) {
            return json(['msg' => '没有数据','code' => 2]);
        }

        if(isset($data['work_year'])){
            $data['work_year'] = (int)$data['work_year'];
        }
        if (isset($data['age'])) {
            $data['age'] = (int)$data['age'];
        }

        if (isset($data['expected_money'])) {
            $money = preg_replace("/(到|至)/",'-',$data['expected_money']);
            $money = explode('-',$data['expected_money']);

            if (count($money) > 1) {
                $data['expected_money_start'] = (int)$money[0];
                $data['expected_money_end'] = (int)$money[1];
            }
            else{
                $data['expected_money_start'] = (int)$money[0];
                $data['expected_money_end'] = (int)$money[0];
            }
        }
        $resume = new ResumeModel();
        $res = $resume->edit($data);
        $data = $resume->getOne(['id' => $data['id']]);
        if ($data && $res) {
            return json(['msg' => '修改成功','code' => 0,'data' => $data]);
        }
        else{
            return json(['msg' => '修改失败，请刷新','code' => 1]);
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
        $path = input('resume_url');
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

    public function downloadResume(){
        //下载简历
        $parm = input('get.');
        header("Content-type:text/html;charset=utf-8"); 
        $file_path = $parm['url'];
        try{
            $fp = fopen($file_path,"rb");
        }catch(\Exception $e){
            $this->error('文件已经不存在');
        }

        $file_size=filesize($file_path);
        //下载文件需要用到的头
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes"); 
        Header("Accept-Length:".$file_size); 
        Header("Content-Disposition: attachment; filename=".$parm['file_name']); 
        //================重点====================
        ob_clean();
        flush();
        //=================重点===================
        $buffer=1024; 
        $file_count=0; 
        //向浏览器返回数据 
        while(!feof($fp) && $file_count<$file_size){ 
            $file_con=fread($fp,$buffer); 
            $file_count+=$buffer; 
            echo $file_con; 
        }
        fclose($fp);

    
    }
    public function test(){
        $sphinx = new \SphinxClient;
        $sphinx->setServer("localhost", 9312);
        $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //匹配模式 ANY为关键词自动拆词，ALL为不拆词匹配（完全匹配），EXTENDED2,多词匹配
        $sphinx->SetArrayResult ( true );   //返回的结果集为数组
        $result = $sphinx->query("@name 貂","resume");   //星号为所有索引源
        $res = $sphinx->query("@sex 男","resume");   //星号为所有索引源
        // $result = $sphinx->query('"高并发"|"c++"',"resume");   //星号为所有索引源
        // $res = $sphinx->UpdateAttributes ('users',array('is_del'),array(18 => array(1)));

        dump($result);
        dump($res);
    }

    public function search($where = ''){
        $resume = new ResumeModel();
        $sphinx = new \SphinxClient;
        $sphinx->setServer("localhost", 9312);
        $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //匹配模式 ANY为关键词自动拆词，ALL为不拆词匹配（完全匹配），EXTENDED2,多词匹配
        $sphinx->SetArrayResult ( true );   //返回的结果集为数组
        $result = $sphinx->query("@name 貂","resume");   //星号为所有索引源
        $res = $sphinx->query("@sex 男","resume");   //星号为所有索引源
        // $result = $sphinx->query('"高并发"|"c++"',"resume");   //星号为所有索引源
        // $res = $sphinx->UpdateAttributes ('users',array('is_del'),array(18 => array(1)));
        $arr = [];
        $arr['name'] = isset($where['name'])?$where['name']:'';
        $arr['sex'] = isset($where['sex'])?$where['sex']:'';
        $arr['educational'] = isset($where['educational'])?$where['educational']:'';
        $arr['phone'] = isset($where['phone'])?$where['phone']:'';
        $arr['expected_job'] = isset($where['expected_job'])?$where['expected_job']:'';
        $arr['status'] = isset($where['status'])?$where['status']:'';
        $arr['school'] = isset($where['school'])?$where['school']:'';
        $arr['speciality'] = isset($where['speciality'])?$where['speciality']:'';
        $arr['english'] = isset($where['english'])?$where['english']:'';
        $phinx_where = '';
        $count_arr = count($arr);
        $arr_ids = [];
        $n = 1;
        foreach ($arr as $k => $v) {
            if ($v == '') {
                continue;
            }
            if ($count_arr == $n) {

                $phinx_where.= "@$k $v";
            }
            else{
                $phinx_where.= "@$k $v & ";
            }
            $n++;
        }

        $data1 = $sphinx->query($phinx_where,"resume");   //基础资料\
        if ($data1) {
            $arr_ids[] = array_column($data1['matches'], 'id');
        }

        $email = isset($where['email'])?$where['email']:'';
        if ($email) {
            
            $data2 = $resume->get(['email' => $email]);
            if ($data2) {
                $arr_ids[] = $data2[0]['id'];
            }   
            else{
                // $arr_ids[] = [];
            }
        }
        

        $money_st = isset($where['expected_money_st'])?$where['expected_money_st']:'';
        $money_ed = isset($where['expected_money_ed'])?$where['expected_money_ed']:'';
        $data3 = '';
        if ($money_st && $money_ed) {
            $data3 = $resume->get('expected_money_start >='.$money_st.' and expected_money_ed =<'.$money_ed);
        }else if($money_st && !$money_ed){  //期望薪资
            $data3 = $resume->get('expected_money_start >='.$money_st);
        }else if(!$money_st && $money_ed){
            $data3 = $resume->get('expected_money_ed =<'.$money_ed);
        }else{
            // $arr_ids[] = [];
        }
        if ($data3) {
            $arr_ids[] = array_column($data3['matches'], 'id');
        }


        $age_min = isset($where['age_min'])?$where['age_min']:'';
        $age_max = isset($where['age_max'])?$where['age_max']:'';
        $data4 = '';
        if ($age_min && $age_max) {
            $data4 = $resume->get('age >='.$age_min.' and age =<'.$age_max);
        }else if($age_min && !$age_max){    //年龄
            $data4 = $resume->get('age >='.$age_min);
        }else if(!$age_min && $age_max){
            $data4 = $resume->get('age =<'.$age_max);
        }else{
            // $arr_ids[] = [];
        }
        if ($data4) {
            $arr_ids[] = array_column($data4['matches'],'id');
        }

        $work_year_min = isset($where['work_year_min'])?$where['work_year_min']:'';
        $work_year_max = isset($where['work_year_max'])?$where['work_year_max']:'';
        $data5 = '';
        if ($work_year_min && $work_year_max) {
            $data5 = $resume->get('work_year >='.$work_year_min.' and work_year =<'.$work_year_max);
        }else if($work_year_min && !$work_year_max){    //年龄
            $data5 = $resume->get('work_year >='.$work_year_min);
        }else if(!$work_year_min && $work_year_max){
            $data5 = $resume->get('work_year =<'.$work_year_max);
        }else{
            // $arr_ids[] = [];
        }
        if ($data5) {
            $arr_ids[] = array_column($data5['matches'],'id');
        }

        $other = isset($where['other'])?$where['other']:'';
        $data6 = '';
        if ($other) {
            $other = preg_replace("/(,|，)/",',',$other);
            $other = explode(',',$other);
            $other = implode('"|"',$other);
            $other = "'".'"'.$other.'"'."'";
            $data6 = $sphinx->query($other,"resume");
            $arr_ids[] = array_column($data6['matches'],'id');
        }
        else{
            // $arr_ids[] = [];
        }

        $ids = [];
        foreach ($arr_ids as $a => $b) {
            if ($a == 0) {
                $ids = $b;
                continue;
            }
            if (empty($b)) {
                continue;
            }
            $ids = array_intersect($ids, $b);
        }
        dump($arr_ids);
        dump($ids);
        exit;
    }


}
