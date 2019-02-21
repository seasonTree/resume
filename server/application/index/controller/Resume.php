<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
use app\index\model\ResumeUpload;
use app\index\model\Resume as ResumeModel;
use app\index\model\User;
use app\index\model\JobSel;
use PHPExcel_IOFactory;
use PHPExcel;
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/Analysis.php';
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/phpanalysis/phpanalysis.class.php';

class Resume extends Controller
{	
    public function checkName(){
        $name = input('get.name');
        $id = input('get.id');
        $data = model('resume')->get(['name'=>$name])->toArray();
        foreach ($data as $k => $v) {
            if ($v['id'] == $id) {
                unset($data[$k]);
            }
        }
        return json(['code' => 0,'msg' => 'ok','data' => array_merge($data)]);
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
        $list = [];
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
                    
                    // unset($rule[$n]);
                    if (!array_key_exists($n, $arr)) {
                        $arr[$n] = $preg[0];
                    }
                    
                    $list[] = $preg[0];
                    // dump($rule);
                }
                
            }
            
            
        }
        if (isset($arr['graduation_time'])) {
            
            $arr['graduation_time'] = preg_replace("/(至|--|到)/",'-',$arr['graduation_time']);

            if (strpos($arr['graduation_time'], '-今')) {
                $arr['graduation_time'] = preg_replace("/-今/",'至今',$arr['graduation_time']);
            }
           
        }
        $arr['educational_background'] = implode("\n", $list);
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
            $v = preg_replace("/^(自我介绍|自我评价|自我描述)(:|：)?/",'',$v);
            if ($v == '') {
                continue;
            }
            $str.= trim($v)."\n";
        }
        return $str;
    }

    public function skillExpertise($parm){
        //技能专长
        $str = '';
        $rule = config('config.basicData.english');
        $arr = [];
        foreach ($parm as $k => $v) {
            if ($v == '') {
                continue;
            }
            if (preg_match($rule,$v,$matches)) {
                $arr['english'] = $matches[0];
            }
            $str.= trim($v)."\n";
        }
        $arr['skillExpertise'] = $str;
        return $arr;
    }

    public function certificate($parm){
        //获奖证书
        return implode("\n",$parm);
    }

    public function language($parm){
        //语言能力
        return implode("\n",$parm);
    }

    public function train($parm){
        //培训经历
        return implode("\n",$parm);
    }

    public function other($parm){
        //其他信息
        return implode("\n",$parm);
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
        //再次处理获奖证书，语言能力，
        $list['certificate'] = isset($list['certificate'])?$list['certificate']:' ';
        $list['certificate'] = isset($list['language'])?$list['certificate']."\n".$list['language']:$list['certificate'];
        $list['certificate'] = isset($list['train'])?$list['certificate']."\n".$list['train']:$list['certificate'];
        $list['skillExpertise'] = isset($list['skillExpertise'])?$list['skillExpertise']:' ';
        $list['skillExpertise'] = isset($list['other'])?$list['skillExpertise']."\n".$list['other']:$list['skillExpertise'];
        unset($list['other']);
        //处理毕业时间
        if ($list['graduation_time'] != '') {
            $graduation_time = explode('-',$list['graduation_time']);
            if (count($graduation_time) > 1) {
                preg_match("/\d{4}/", $graduation_time[1],$res);
                $list['graduation_time'] = $res[0] == ''?'暂无':$res[0];
            }
            else{
                preg_match("/\d{4}/", $graduation_time[0],$res);
                $list['graduation_time'] = $res[0];
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
        dump($data);exit;
        $personal_name = array_column($data,'personal_name');
        $phone = array_column($data,'phone');
        $check_data = '';
        $resume = new ResumeModel();
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
        $info = $file->validate(['size'=>20971520,'ext'=>'xlsx,xls'])->move($path);
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
        $info = $file->validate(['size'=>20971520,'ext'=>'xls,xlsx,doc,docx,html,htm,mht'])->move($path);
        if($info){
            $upload = new ResumeUpload();
            $file_name = preg_replace("/\s/",'_',$_FILES['file']['name']);
            $find = $upload->getOne(['file_name' => $file_name,'resume_id' => input('resume_id')]);
            if ($find) {
                return json(['msg' => '文件已经存在','code' => 3]);
            }

            $data = array(
                'resume_url' => 'uploads/'.$info->getSaveName(),
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
        // if (count($input) > 3 ) {
            $data = $this->search($input);
            $count = array_pop($data);
        // }
        // else{
            // $resume = new ResumeModel();
        //     $data = $resume->get();
            // $count = $resume->getCount();
        // }
        
        return json(['msg' => '获取成功','code' => 0,'data' => [ 'row' => $data,'total' => $count]]);
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
        $resume = new ResumeModel();
        $res = $resume->getOne(['name' => $data['name'],'phone' => $data['phone']]);
        if ($res) {
            return json(['msg' => '该候选人已存在，不允许添加','code' => 3]);
        }
        if (empty($data)) {
            return json(['msg' => '没有数据','code' => 2]);
        }

        if (!isset($data['name']) || $data['name'] == '') {
            return json(['msg' => '姓名必填','code' => 3]);
        }

        if (!isset($data['phone']) || $data['phone'] == '') {
            return json(['msg' => '电话必填','code' => 4]);
        }

        if (!isset($data['educational']) || $data['educational'] == '') {
            return json(['msg' => '学历必填','code' => 5]);
        }

        if (!isset($data['work_year']) || $data['work_year'] == '') {
            return json(['msg' => '工作年限必填','code' => 6]);
        }

        if (!isset($data['graduation_time']) || $data['graduation_time'] == '') {
            return json(['msg' => '毕业时间必填','code' => 7]);
        }

        if (!isset($data['source']) || $data['source'] == '') {
            return json(['msg' => '简历来源必填','code' => 8]);
        }

        if (!isset($data['expected_job']) || $data['expected_job'] == '') {
            return json(['msg' => '岗位必填','code' => 9]);
        }

        if (!isset($data['email']) || $data['email'] == '') {
            return json(['msg' => '电子邮箱','code' => 10]);
        }

        if (!isset($data['school']) || $data['school'] == '') {
            return json(['msg' => '毕业院校必填','code' => 11]);
        }

        $data['ct_user'] = Session::get('user_info')['uname'];

        if(isset($data['work_year'])){
            if (preg_match("/\d+/",$data['work_year'],$matches)) {
                 $data['work_year'] = $matches[0];
            }
            
        }
        if (isset($data['age'])) {
             if (preg_match("/\d+/",$data['age'],$matches)) {
                 $data['age'] = $matches[0];
            }
            // $data['age'] = (int)$data['age'];
        }

        if (isset($data['expected_money']) && $data['expected_money'] != '') {
            $money = preg_replace("/(到|至)/",'-',$data['expected_money']);
            $money = preg_replace("/(K|k)/",'000',$money);//把k替换成3个0
            $money = explode('-',$money);
            if (count($money) > 1) {
                if (preg_match("/\d+/",$money[0],$matches)) {
                    $data['expected_money_start'] = $matches[0];
                }
                if (preg_match("/\d+/",$money[1],$matches)) {
                    $data['expected_money_end'] = $matches[0];
                    
                }
                if ($data['expected_money_start'] <= 100) {//智能优化，后面乘1000
                    $data['expected_money_start'] = $data['expected_money_start'].'000';
                }
                if ($data['expected_money_end'] <= 100) {
                    $data['expected_money_end'] = $data['expected_money_end'].'000';
                }

            }
            else{
                if (preg_match("/\d+/",$money[0],$matches)) {
                    $data['expected_money_start'] = $matches[0];
                    $data['expected_money_end'] = $matches[0];
                    if ($data['expected_money_start'] <= 100) {//智能优化，后面乘1000
                        $data['expected_money_start'] = $data['expected_money_start'].'000';
                    }
                    if ($data['expected_money_end'] <= 100) {
                        $data['expected_money_end'] = $data['expected_money_end'].'000';
                    }


                }
                // $data['expected_money_start'] = (int)$money[0];
                // $data['expected_money_end'] = (int)$money[0];
            }
        }

        
        
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
            if (preg_match("/\d+/",$data['work_year'],$matches)) {
                 $data['work_year'] = $matches[0];
            }
            
        }
        if (isset($data['age'])) {
             if (preg_match("/\d+/",$data['age'],$matches)) {
                 $data['age'] = $matches[0];
            }
            // $data['age'] = (int)$data['age'];
        }

        if (isset($data['expected_money']) && $data['expected_money'] != '') {
            $money = preg_replace("/(到|至)/",'-',$data['expected_money']);
            $money = preg_replace("/(K|k)/",'000',$money);//把k替换成3个0
            $money = explode('-',$money);
            if (count($money) > 1) {
                if (preg_match("/\d+/",$money[0],$matches)) {
                    $data['expected_money_start'] = $matches[0];
                }
                if (preg_match("/\d+/",$money[1],$matches)) {
                    $data['expected_money_end'] = $matches[0];
                }

                if ($data['expected_money_start'] <= 100) {//智能优化，后面乘1000
                    $data['expected_money_start'] = $data['expected_money_start'].'000';
                }
                if ($data['expected_money_end'] <= 100) {
                    $data['expected_money_end'] = $data['expected_money_end'].'000';
                }
            }
            else{
                if (preg_match("/\d+/",$money[0],$matches)) {
                    $data['expected_money_start'] = $matches[0];
                    $data['expected_money_end'] = $matches[0];

                if ($data['expected_money_start'] <= 100) {//智能优化，后面乘1000
                    $data['expected_money_start'] = $data['expected_money_start'].'000';
                }
                if ($data['expected_money_end'] <= 100) {
                    $data['expected_money_end'] = $data['expected_money_end'].'000';
                }
                }
                // $data['expected_money_start'] = (int)$money[0];
                // $data['expected_money_end'] = (int)$money[0];
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
        $file_path = dirname(Env::get('ROOT_PATH')).'/client/dist/'.$parm['url'];
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
    public function test($where = []){
        $where['pageSize'] = 100000000;
        $where['pageIndex'] = 1;
        $resume = new ResumeModel();
        $email = isset($where['email'])?$where['email']:'';
        if ($email) {
            //先判断邮箱搜索条件
            $data = $resume->getId(['email' => $email]);
            if (isset($data2[0])) {
                $ids = [];
                foreach ($data as $k => $v) {
                    $ids[] = $v['id']; 
                }
                $arr_ids[] = $ids;
            }   
            else{
                // $arr_ids[] = [];
                return [];
            }
        }

        $sphinx = new \SphinxClient;
        $sphinx->setServer("192.168.199.134", 9312);
        $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //匹配模式 ANY为关键词自动拆词，ALL为不拆词匹配（完全匹配），EXTENDED2,多词匹配
        $sphinx->SetArrayResult ( true );   //返回的结果集为数组
        

        // $money_st = isset($where['expected_money_st'])?$where['expected_money_st']:'';
        // $money_ed = isset($where['expected_money_ed'])?$where['expected_money_ed']:'';
        // if ($money_st && $money_ed) {
        //     $sphinx->SetFilterRange('expected_money_start',$money_st,$money_ed);
        //     $sphinx->SetFilterRange('expected_money_end',$money_st,$money_ed);
        //     // $sphinx->SetFilterRange('expected_money_end', 0, $money_st);
        // }else if($money_st && !$money_ed){  //期望薪资
        //     $sphinx->SetFilterRange('expected_money_start', $money_st, 100000000);
        //     // $sphinx->SetFilterRange('expected_money_end', 0,$money_st);
        // }else if(!$money_st && $money_ed){
        //     // $sphinx->SetFilterRange('expected_money_end', $money_ed,100000000);
        //     $sphinx->SetFilterRange('expected_money_end',0,$money_ed);
        // }else{
        //     // $arr_ids[] = [];
        // }

        $age_min = isset($where['age_min'])?$where['age_min']:'';
        $age_max = isset($where['age_max'])?$where['age_max']:'';
        if ($age_min && $age_max) {
            $sphinx->SetFilterRange('age', $age_min, $age_max);//查找年龄最小-最大之间
        }else if($age_min && !$age_max){    //年龄
            $sphinx->SetFilterRange('age', $age_min, 100);//查找年龄最小-100之间
        }else if(!$age_min && $age_max){
            $sphinx->SetFilterRange('age', 0, $age_max);//查找年龄0-最大之间
        }else{
            // $arr_ids[] = [];
        }

        $work_year_min = isset($where['work_year_min'])?$where['work_year_min']:'';
        $work_year_max = isset($where['work_year_max'])?$where['work_year_max']:'';
        if ($work_year_min && $work_year_max) {
            $sphinx->SetFilterRange('work_year', $work_year_min, $work_year_max);
        }else if($work_year_min && !$work_year_max){    
            $sphinx->SetFilterRange('work_year', $work_year_min, 100);
        }else if(!$work_year_min && $work_year_max){
            $sphinx->SetFilterRange('work_year', 0, $work_year_max);
        }else{
            // $arr_ids[] = [];
            
        }


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
        if($phinx_where != ''){
            // $sphinx->AddQuery($phinx_where,'resume');
            $phinx_where = '('.$phinx_where.')';
        }

        $other = isset($where['other'])?$where['other']:'';
        if ($other) {
            $other = preg_replace("/(,|，)/",',',$other);
            $other = explode(',',$other);
            $other = implode('"|"',$other);
            // $other = "'".'"'.$other.'"'."'";
            $other = '("'.$other.'")';
            $phinx_where = empty($phinx_where)?$other:$phinx_where.' & '.$other;
            
            // $sphinx->AddQuery($other,'resume');
        }
        // $data = $sphinx->RunQueries();
        $sphinx->SetLimits(($where['pageIndex'] - 1) * $where['pageSize'] , $where['pageSize'] , 3000);
        $res = $sphinx->query('','resume');
        dump($res);exit;
        $data = [];

        $money_st = isset($where['expected_money_st'])?$where['expected_money_st']:'';
        $money_ed = isset($where['expected_money_ed'])?$where['expected_money_ed']:'';
        //筛选薪资范围

        if (isset($res['matches'])) {
            $data_arr = $res['matches'];
            $data_num = 0;//定义数据量
            $page_end = $where['pageSize'] * $where['pageIndex'];
            $page_start = $page_end - $where['pageSize'];

            $list_arr = array_slice($data_arr,$page_start,$where['pageSize']);
            foreach ($list_arr as $k => $v) {
                // if ($k < $page_start) {
                //     continue;//分页处理，过滤不符合要求的数据
                // }
                // if ($data_num == $where['pageSize']) {
                //     break;//数据取够之后直接跳出循环
                // }
                if ($money_st && $money_ed) {
                    $check_start = false;
                    if($money_st >= $v['attrs']['expected_money_start'] && $money_st <= $v['attrs']['expected_money_end']){
                        $check_start = true;
                    }
                    $check_end = false;
                    if($money_ed >= $v['attrs']['expected_money_start'] && $money_ed <= $v['attrs']['expected_money_end']){
                        $check_end = true;
                    }
                    $check_between = false;
                    if($money_st <= $v['attrs']['expected_money_start'] && $money_ed >= $v['attrs']['expected_money_end']){
                        $check_between = true;
                    }
                    if ($check_start || $check_end || $check_between) {
                        $data[$k] = $v['attrs'];
                        $data[$k]['id'] = $v['id'];
                    }
                }else if($money_st && !$money_ed){  //期望薪资
                    $check = false;
                    if($money_st >= $v['attrs']['expected_money_start'] && $money_st <= $v['attrs']['expected_money_end']){
                        $check = true;
                    }
                    if ($money_st <= $v['attrs']['expected_money_end'] || $check) {

                        $data[$k] = $v['attrs'];
                        $data[$k]['id'] = $v['id'];
                    }
                    
                }else if(!$money_st && $money_ed){
                    $check = false;
                    if($money_ed >= $v['attrs']['expected_money_start'] && $money_ed <= $v['attrs']['expected_money_end']){
                        $check = true;
                    }

                    if ($money_ed >= $v['attrs']['expected_money_end'] || $check) {
                        $data[$k] = $v['attrs'];
                        $data[$k]['id'] = $v['id'];
                    }
                }else{
                    $data[$k] = $v['attrs'];
                    $data[$k]['id'] = $v['id'];
                }

                // $data_num++;
                
            }
            $data[] = count($data_arr);//总数

        }
        dump($data);
    }

    // public function search($where = ''){
    //     $resume = new ResumeModel();
    //     $sphinx = new \SphinxClient;
    //     $sphinx->setServer("192.168.199.134", 9312);
    //     $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //匹配模式 ANY为关键词自动拆词，ALL为不拆词匹配（完全匹配），EXTENDED2,多词匹配
    //     $sphinx->SetArrayResult ( true );   //返回的结果集为数组
    //     // $result = $sphinx->query("@name 貂","resume");   //星号为所有索引源
    //     // $res = $sphinx->query("@sex 男","resume");   //星号为所有索引源
    //     // $result = $sphinx->query('"高并发"|"c++"',"resume");   //星号为所有索引源
    //     // $res = $sphinx->UpdateAttributes ('users',array('is_del'),array(18 => array(1)));
    //     $arr = [];
    //     $arr['name'] = isset($where['name'])?$where['name']:'';
    //     $arr['sex'] = isset($where['sex'])?$where['sex']:'';
    //     $arr['educational'] = isset($where['educational'])?$where['educational']:'';
    //     $arr['phone'] = isset($where['phone'])?$where['phone']:'';
    //     $arr['expected_job'] = isset($where['expected_job'])?$where['expected_job']:'';
    //     $arr['status'] = isset($where['status'])?$where['status']:'';
    //     $arr['school'] = isset($where['school'])?$where['school']:'';
    //     $arr['speciality'] = isset($where['speciality'])?$where['speciality']:'';
    //     $arr['english'] = isset($where['english'])?$where['english']:'';
    //     $phinx_where = '';
    //     $count_arr = count($arr);
    //     $arr_ids = [];
    //     $n = 1;
    //     foreach ($arr as $k => $v) {
    //         if ($v == '') {
    //             continue;
    //         }
    //         if ($count_arr == $n) {

    //             $phinx_where.= "@$k $v";
    //         }
    //         else{
    //             $phinx_where.= "@$k $v & ";
    //         }
    //         $n++;
    //     }
    //     if($phinx_where != ''){
    //         $data1 = $sphinx->query($phinx_where,"resume");   //基础资料\
        
    //         if (isset($data1['matches'])) {
    //             $arr_ids[] = array_column($data1['matches'], 'id');
    //         }
    //         else{
    //             return [];
    //         }
    //     }

    //     $email = isset($where['email'])?$where['email']:'';
    //     if ($email) {
            
    //         $data2 = $resume->getId(['email' => $email]);
    //         if (isset($data2[0])) {
    //             $arr_ids[] = [ 0 => $data2[0]['id']];
    //         }   
    //         else{
    //             // $arr_ids[] = [];
    //             return [];
    //         }
    //     }
        

    //     $money_st = isset($where['expected_money_st'])?$where['expected_money_st']:'';
    //     $money_ed = isset($where['expected_money_ed'])?$where['expected_money_ed']:'';
    //     $data3 = '';
    //     if ($money_st && $money_ed) {
    //         $data3 = $resume->getId('expected_money_start >='.$money_st.' and expected_money_start <='.$money_ed.' and expected_money_end >='.$money_st);
    //     }else if($money_st && !$money_ed){  //期望薪资
    //         $data3 = $resume->getId('expected_money_start >='.$money_st);
    //     }else if(!$money_st && $money_ed){
    //         $data3 = $resume->getId('expected_money_end <='.$money_ed);
    //     }else{
    //         // $arr_ids[] = [];
    //     }
    //     if ($data3) {
    //         $arr_ids[] = array_column($data3, 'id');
    //     }
       


    //     $age_min = isset($where['age_min'])?$where['age_min']:'';
    //     $age_max = isset($where['age_max'])?$where['age_max']:'';
    //     $data4 = '';
    //     if ($age_min && $age_max) {
    //         $data4 = $resume->getId('age >='.$age_min.' and age <='.$age_max);
    //     }else if($age_min && !$age_max){    //年龄
    //         $data4 = $resume->getId('age >='.$age_min);
    //     }else if(!$age_min && $age_max){
    //         $data4 = $resume->getId('age <='.$age_max);
    //     }else{
    //         // $arr_ids[] = [];
            
    //     }
    //     if ($data4) {
    //         $arr_ids[] = array_column($data4,'id');
    //     }
        
    //     $work_year_min = isset($where['work_year_min'])?$where['work_year_min']:'';
    //     $work_year_max = isset($where['work_year_max'])?$where['work_year_max']:'';
    //     $data5 = '';
    //     if ($work_year_min && $work_year_max) {
    //         $data5 = $resume->getId('work_year >='.$work_year_min.' and work_year <='.$work_year_max);
    //     }else if($work_year_min && !$work_year_max){    
    //         $data5 = $resume->getId('work_year >='.$work_year_min);
    //     }else if(!$work_year_min && $work_year_max){
    //         $data5 = $resume->getId('work_year <='.$work_year_max);
    //     }else{
    //         // $arr_ids[] = [];
            
    //     }
    //     if ($data5) {
    //         $arr_ids[] = array_column($data5,'id');
    //     }

    //     $other = isset($where['other'])?$where['other']:'';
    //     $data6 = '';
    //     if ($other) {
    //         $other = preg_replace("/(,|，)/",',',$other);
    //         $other = explode(',',$other);
    //         $other = implode('"|"',$other);
    //         $other = "'".'"'.$other.'"'."'";
    //         $data6 = $sphinx->query($other,"resume");
    //         if (isset($data6['matches'])) {
    //             $arr_ids[] = array_column($data6['matches'],'id');
    //         }
    //         else{
    //             return [];
    //         }
            
    //     }


    //     $ids = [];
    //     foreach ($arr_ids as $a => $b) {
    //         if ($a == 0) {
    //             $ids = $b;
    //             continue;
    //         }
    //         $ids = array_intersect($ids, $b);
    //     }
    //     if (!empty($ids)) {
    //         $str = implode(',', $ids);
    //         $data = $resume->get("id in($str)");
    //     }
    //     else{
    //         $data = [];
    //     }
        
    //     return $data;
    // }

    public function search($where = ''){
        $resume = new ResumeModel();
        $email = isset($where['email'])?$where['email']:'';
        if ($email) {
            //先判断邮箱搜索条件
            $data = $resume->getId(['email' => $email]);
            if (isset($data2[0])) {
                $ids = [];
                foreach ($data as $k => $v) {
                    $ids[] = $v['id']; 
                }
                $arr_ids[] = $ids;
            }   
            else{
                // $arr_ids[] = [];
                return [];
            }
        }

        $sphinx = new \SphinxClient;
        $sphinx->setServer("192.168.199.134", 9312);
        $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //匹配模式 ANY为关键词自动拆词，ALL为不拆词匹配（完全匹配），EXTENDED2,多词匹配
        $sphinx->SetArrayResult ( true );   //返回的结果集为数组
        // $sphinx->SetLimits(0 , 100000000 , 6000);
        $sphinx->SetLimits(($where['pageIndex'] - 1) * $where['pageSize'] , $where['pageSize'] , 3000);//分页

        $money_st = isset($where['expected_money_st'])?$where['expected_money_st']:'';
        $money_ed = isset($where['expected_money_ed'])?$where['expected_money_ed']:'';
        if ($money_st && $money_ed) {
            $sphinx->SetFilterRange('expected_money_start',$money_st,$money_ed);
            $sphinx->SetFilterRange('expected_money_end',$money_st,$money_ed);
            // $sphinx->SetFilterRange('expected_money_end', 0, $money_st);
        }else if($money_st && !$money_ed){  //期望薪资
            $sphinx->SetFilterRange('expected_money_start', $money_st, 100000000);
            // $sphinx->SetFilterRange('expected_money_end', 0,$money_st);
        }else if(!$money_st && $money_ed){
            // $sphinx->SetFilterRange('expected_money_end', $money_ed,100000000);
            $sphinx->SetFilterRange('expected_money_end',0,$money_ed);
        }else{
            // $arr_ids[] = [];
        }

        $age_min = isset($where['age_min'])?$where['age_min']:'';
        $age_max = isset($where['age_max'])?$where['age_max']:'';
        if ($age_min && $age_max) {
            $sphinx->SetFilterRange('age', $age_min, $age_max);//查找年龄最小-最大之间
        }else if($age_min && !$age_max){    //年龄
            $sphinx->SetFilterRange('age', $age_min, 100);//查找年龄最小-100之间
        }else if(!$age_min && $age_max){
            $sphinx->SetFilterRange('age', 0, $age_max);//查找年龄0-最大之间
        }else{
            // $arr_ids[] = [];
        }

        $work_year_min = isset($where['work_year_min'])?$where['work_year_min']:'';
        $work_year_max = isset($where['work_year_max'])?$where['work_year_max']:'';
        if ($work_year_min && $work_year_max) {
            $sphinx->SetFilterRange('work_year', $work_year_min, $work_year_max);
        }else if($work_year_min && !$work_year_max){    
            $sphinx->SetFilterRange('work_year', $work_year_min, 100);
        }else if(!$work_year_min && $work_year_max){
            $sphinx->SetFilterRange('work_year', 0, $work_year_max);
        }else{
            // $arr_ids[] = [];
            
        }


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
        if($phinx_where != ''){
            // $sphinx->AddQuery($phinx_where,'resume');
            $phinx_where = '('.$phinx_where.')';
        }

        $other = isset($where['other'])?$where['other']:'';
        if ($other) {
            $other = preg_replace("/(,|，)/",',',$other);
            $other = explode(',',$other);
            $other = implode('"|"',$other);
            // $other = "'".'"'.$other.'"'."'";
            $other = '("'.$other.'")';
            $phinx_where = empty($phinx_where)?$other:$phinx_where.' & '.$other;
            
            // $sphinx->AddQuery($other,'resume');
        }
        // $data = $sphinx->RunQueries();
        $res = $sphinx->query($phinx_where,'resume');

        // dump($res);exit;
        $data = [];

        if (isset($res['matches'])) {
            foreach ($res['matches'] as $k => $v) {
                $data[$k] = $v['attrs'];
                $data[$k]['id'] = $v['id'];
            }
        }

        $data[] = $res['total'];

        // $money_st = isset($where['expected_money_st'])?$where['expected_money_st']:'';
        // $money_ed = isset($where['expected_money_ed'])?$where['expected_money_ed']:'';
        // //筛选薪资范围

        // if (isset($res['matches'])) {
        //     $data_arr = $res['matches'];
        //     $data_num = 0;//定义数据量
        //     $page_end = $where['pageSize'] * $where['pageIndex'];
        //     $page_start = $page_end - $where['pageSize'];

        //     $list_arr = array_slice($data_arr,$page_start,$where['pageSize']);
        //     foreach ($list_arr as $k => $v) {
        //         // if ($k < $page_start) {
        //         //     continue;//分页处理，过滤不符合要求的数据
        //         // }
        //         // if ($data_num == $where['pageSize']) {
        //         //     break;//数据取够之后直接跳出循环
        //         // }
        //         if ($money_st && $money_ed) {
        //             $check_start = false;
        //             if($money_st >= $v['attrs']['expected_money_start'] && $money_st <= $v['attrs']['expected_money_end']){
        //                 $check_start = true;
        //             }
        //             $check_end = false;
        //             if($money_ed >= $v['attrs']['expected_money_start'] && $money_ed <= $v['attrs']['expected_money_end']){
        //                 $check_end = true;
        //             }
        //             $check_between = false;
        //             if($money_st <= $v['attrs']['expected_money_start'] && $money_ed >= $v['attrs']['expected_money_end']){
        //                 $check_between = true;
        //             }
        //             if ($check_start || $check_end || $check_between) {
        //                 $data[$k] = $v['attrs'];
        //                 $data[$k]['id'] = $v['id'];
        //             }
        //         }else if($money_st && !$money_ed){  //期望薪资
        //             $check = false;
        //             if($money_st >= $v['attrs']['expected_money_start'] && $money_st <= $v['attrs']['expected_money_end']){
        //                 $check = true;
        //             }
        //             if ($money_st <= $v['attrs']['expected_money_end'] || $check) {

        //                 $data[$k] = $v['attrs'];
        //                 $data[$k]['id'] = $v['id'];
        //             }
                    
        //         }else if(!$money_st && $money_ed){
        //             $check = false;
        //             if($money_ed >= $v['attrs']['expected_money_start'] && $money_ed <= $v['attrs']['expected_money_end']){
        //                 $check = true;
        //             }

        //             if ($money_ed >= $v['attrs']['expected_money_end'] || $check) {
        //                 $data[$k] = $v['attrs'];
        //                 $data[$k]['id'] = $v['id'];
        //             }
        //         }else{
        //             $data[$k] = $v['attrs'];
        //             $data[$k]['id'] = $v['id'];
        //         }

        //         // $data_num++;
                
        //     }
        //     $data[] = count($data_arr);//总数

        // }
        return $data;
    }

    public function getJob(){
        //获取岗位全部下拉
        $job_model = new JobSel();
        $data = $job_model->field('job_name')->select()->toArray();
        return json(['msg' => '获取成功','code' => 0,'data' => array_column($data,'job_name')]);
    }

    public function getJobById(){
        //根据id获取职位
        $id = input('id');
        $job_model = new JobSel();
        $data = $job_model->field('id,job_name,mfy_time')->where('id='.$id)->find();
        return json(['msg' => '获取成功','code' => 0,'data' => $data]);
    }

    public function JobList(){
        //职位列表
        $pageSize = input('get.pageSize');
        $pageIndex = input('get.pageIndex');
        $begin = ($pageIndex-1)*$pageSize;
        $name = input('get.name');
        isset($name)?$where['job_name'] = $name:$where = '1=1';
        $job_model = new JobSel();
        $data = $job_model->where($where)->limit($begin,$pageSize)->order('id esc')->select();
        $total = $job_model->where($where)->count('id');
        return json(['msg' => '获取成功','code' => 0,'data' => ['row' => $data,'total' => $total]]);

    }

    public function addJob(){
        //添加职位
        $job_model = new JobSel();
        $data['job_name'] = input('post.job_name');
        $find = $job_model->where('job_name','=',$data['job_name'])->find();
        if ($find) {
            return json(['msg' => '该职位已存在','code' => 2,'data' => []]);
        }
        $data['ct_user'] = Session::get('user_info')['uname'];
        $res = $job_model->insertGetId($data);
        if ($res) {
           $data['id'] = $res;
           return json(['msg' => '新增成功','code' => 0,'data' => $data]);
        }
        return json(['msg' => '新增失败','code' => 1,'data' => []]);
    }

    public function editJob(){
        //修改职位
        $job_model = new JobSel();
        $edit['id'] = input('post.id');
        $find = $job_model->where('id='.$edit['id'])->find();
        $edit['job_name'] = input('post.job_name');
        $edit['mfy_user'] = Session::get('user_info')['uname'];
        if ($find['mfy_time'] != input('post.mfy_time')) {
            return json(['msg' => '数据已发生改变，请刷新','code' => 1,'data' => []]);
        } 
        $find = $job_model->where('job_name','=',$edit['job_name'])->find();

        if ($find) {
            return json(['msg' => '该职位已存在','code' => 3,'data' => []]);
        }
        $res = $job_model->update($edit);
        if ($res) {
            $data = $job_model->where('id='.$edit['id'])->find();
            return json(['msg' => '修改成功','code' => 0,'data' => $data]);
        }
        else{
            return json(['msg' => '修改失败','code' => 2,'data' =>[]]);
        }

    }

    public function delJob(){
        //删除职位
        $id = input('post.id');
        $job_model = new JobSel();
        $res = $job_model->where('id','=',$id)->delete();
        if ($res) {
            return json(['msg' => '删除成功','code' => 0,'data' => []]);
        }
        return json(['msg' => '修改成功','code' => 0,'data' => []]);
    }

    public function export(){
        //简历导出
        $dest = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/file/'.Session::get('user_info')['uname'].time().'.docx';
        $resume_id = input('get.resume_id');
        $type = input('get.type');

        $option = function($msg){
            if ($msg == 'tonghe') {
                //同和
                return ['image' => dirname(Env::get('ROOT_PATH')).'/client/src/image/tonghe.jpg','file_type' => '同和','image_size' => ['width'=>165,'height'=>53,'align'=>'right']];
            }
            else if ($msg == 'avo') {
                //大展
                return ['image' => dirname(Env::get('ROOT_PATH')).'/client/src/image/avo.jpg','file_type' => '大展','image_size' => ['width'=>90,'height'=>53,'align'=>'right']];
            }
            else{
                //缺少类型或者简历id
                echo json_encode(['msg' => $msg,'code' => 1]);exit;
            }
            
            
        };
        $resume_id == ''?$option('缺少简历ID'):'';
        $result = $type == ''?$option('缺少类型'):$option($type);

        $resume = new ResumeModel();
        $data = $resume->where('id','=',$resume_id)->find();

        if (!$data) {
            return json(['msg' => '数据不存在，请刷新','code' => 2,'data' => []]);
        }
        //开启phpword
        $PHPWord = new \PhpOffice\PhpWord\PhpWord();
        // $PHPWordHelper= new \PhpOffice\PhpWord\Shared\Font();
        $section = $PHPWord->createSection();

        //添加页眉
        $header = $section->createHeader();
        $table = $header->addTable();
        $table->addRow();
        $table->addCell(9000)->addText('');
        $cellStyle = array('borderBottomColor' => '#000000');
        $table->addCell(9000)->addImage($result['image'],$result['image_size']);
        //内容
        //自定义字体
        $PHPWord->addFontStyle('FangSong19pt', array('name'=>'仿宋', 'size'=>19,'bold' => true));
        $PHPWord->addFontStyle('FangSong16pt', array('name'=>'仿宋', 'size'=>16,'bold' => true));
        //姓名
        $section->addText('姓名:'.$data['name'],'FangSong19pt');
        //岗位
        $section->addText('面试职位:'.$data['expected_job'],'FangSong19pt');
        //基本信息
        $content = $section->addTable();
        $cellStyle = array('bgColor' => '#FAF0E6');
        $content->addRow();
        $content->addCell(9000,$cellStyle)->addText('基本信息','FangSong16pt');
        //基本信息填充
        $section->addTextBreak();
        $section->addText($data['sex'].' | '.$data['birthday'].' | '.$data['work_year'].'年工作经验');
        $section->addTextBreak();
        //自我评价
        $content = $section->addTable();
        $cellStyle = array('bgColor' => '#FAF0E6');
        $content->addRow();
        $content->addCell(9000,$cellStyle)->addText('自我评价','FangSong16pt');
        //自我评价填充
        $section->addTextBreak();
        $section->addText($data['selfEvaluation']);
        $section->addTextBreak();
        //工作经验
        $content = $section->addTable();
        $cellStyle = array('bgColor' => '#FAF0E6');
        $content->addRow();
        $content->addCell(9000,$cellStyle)->addText('工作经验','FangSong16pt');
        //工作经验填充
        $section->addTextBreak();
        $workExperience = explode("\n", $data['workExperience']);
        foreach ($workExperience as $k => $v) {
            $v = trim($v);
            // if(preg_match("/(.*)(带领|编写|工作|描述|负责|1,|1\.|1、)(.*)/",$v,$match)){
            //     $temp = str_replace($match[0],'',$v);

            //     $section->addText($temp);
            //     $section->addText($match[0]);
            //     continue;
            // }
            $section->addText($v);
        }
        // $section->addText($data['workExperience']);
        $section->addTextBreak();
        //项目经验
        $content = $section->addTable();
        $cellStyle = array('bgColor' => '#FAF0E6');
        $content->addRow();
        $content->addCell(9000,$cellStyle)->addText('项目经验','FangSong16pt');
        //项目经验填充
        $section->addTextBreak();
        $projectExperience = explode("\n", $data['projectExperience']);
        foreach ($projectExperience as $k => $v) {
            $v = trim($v);
            $section->addText($v);
        }
        // $section->addText($data['projectExperience']);
        $section->addTextBreak();
        //教育经历
        $content = $section->addTable();
        $cellStyle = array('bgColor' => '#FAF0E6');
        $content->addRow();
        $content->addCell(9000,$cellStyle)->addText('教育经历','FangSong16pt');
        //教育经历填充
        $section->addTextBreak();
        $educational_background = explode("\n", $data['educational_background']);
        foreach ($educational_background as $k => $v) {
            $v = trim($v);
            $section->addText($v);
        }
        // $section->addText($data['educational_background']);
        $section->addTextBreak();


        //添加页脚
        $footer = $section->createFooter();
        $footer->addPreserveText('Page {PAGE} of {NUMPAGES}.',array('align'=>'center'));

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord,'Word2007');
        $objWriter->save($dest);

        header("Content-type:text/html;charset=utf-8"); 
        try{
            $fp = fopen($dest,"rb");
        }catch(\Exception $e){
            $this->error('文件已经不存在');
        }
        //下载
        $file_size=filesize($dest);

        //下载文件需要用到的头
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes"); 
        Header("Accept-Length:".$file_size); 
        Header("Content-Disposition: attachment; filename=".$result['file_type'].'-'.$data['name'].'-'.$data['expected_job'].'.docx'); 
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

    public function getSafeStr($str){
        //把字符编码转化成utf-8
        $s1 = iconv('gbk','utf-8//IGNORE',$str);
        $s0 = iconv('utf-8','gbk//IGNORE',$s1);
        if($s0 == $str){
            return $s1;
        }else{
            return $str;
        }
    }



}
