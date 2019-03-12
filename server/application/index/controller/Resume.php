<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
use think\Db;
use app\index\model\ResumeUpload;
use app\index\model\Communicate;
use app\index\model\Resume as ResumeModel;
use app\index\model\User;
use app\index\model\JobSel;
use PHPExcel_IOFactory;
use PHPExcel;
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Element\Text;
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/Analysis.php';
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/phpanalysis/phpanalysis.class.php';
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/Docx.php';

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
        $parm = implode("\n",$parm);
        $parm = preg_replace("/(\s+|:|：|\(|（|\)|）)/","\n",$parm);
        $parm = preg_replace("/姓名|性别|户口|联系电话|联系方式|求职意向|更新时间|智联|智联招聘|前程无忧|个人信息|个人简历|应聘职位|应聘机构|基本信息|基本情况|简历|毕业学校|毕业院校/",'',$parm);//去除多余的信息
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
        $parm = implode("\n",$parm);
        $parm = preg_replace("/年/",'.',$parm);//统一把年换成.
        $parm = preg_replace("/月|教育经历|教育背景|毕业学校|毕业院校/",'',$parm);//统一把月,多余的字符去掉
        $parm = preg_replace("/(至|到|–|—|―)+/",'-',$parm);//统一把范围符换成-
        $parm = preg_replace("/-今/",'至今',$parm);//以防上一步替换"至今"变成-今，因此要替换回去
        $parm = preg_replace("/\s+|\|/","\n",$parm);//空格和|变成换行符

        if(preg_match_all("/\d+(\s+)?(-|至|到|–|—|―)+(\s+)?(\d+|至今)/",$parm,$preg)){//处理时间格式，空格问题,去除空格
            foreach ($preg[0] as $k => $v) {
                $parm = preg_replace("/$v/",preg_replace("/\s+/",'',$v),$parm);
            }
        }
        $arr = [];
        // $arr['educational_background'] = $parm;//整个教育背景输出

        // $parm = preg_replace("/(:|：|\s+|\|)/","\n",$parm);//统一把各种符号换成换行符
        $parm = explode("\n",$parm);//用换行符分割成数组
        
        $list = [];
        $rule = config('config.educationalBackground');


        $del_rule = $rule['speciality_info'];
        unset($rule['speciality_info']);

        $keys = 0;//记录下标
        foreach ($parm as $k => $v) {
            $v = $this->trimall($v);
            // $v = phpanalysis($v);
            if (preg_match($del_rule,$v,$res)) {
                continue;//剔除无用信息
            }
            if (empty($v)) {
                continue;//跳过空
            }

            foreach ($rule as $n => $pattern) {
                if (preg_match($pattern,$v,$preg)) {


                    // $strpos = strpos($v,$preg[0]);
                    // $strlen = strlen($preg[0]);
                    // for ($i = 0;$i < $strlen; $i++) {
                    //     $v[$strpos] = '';
                    //     $strpos++;
                    // }
                    $v = str_replace($preg[0],'',$v);

                    
                    // unset($rule[$n]);
                    if (!array_key_exists($n, $arr)) {
                        $arr[$n] = $preg[0];
                    }

                    if ($n == 'school' || $n == 'graduation_time') {
                        isset($list[$keys][$n])?$keys++:'';
                        $list[$keys][$n] = $preg[0];

                    }
                    if ($n == 'speciality') {
                        isset($list[$keys]['speciality'])?'':$list[$keys]['speciality'] = $preg[0];
                    }

                    if ($n == 'educational') {
                        $list[$keys][$n] = $preg[0];
                    }
                    
                    
                    // dump($rule);
                }

                
            }
            
            
        }
        // if (isset($arr['graduation_time'])) {
            
        //     $arr['graduation_time'] = preg_replace("/(至|-|到|–|—)+/",'-',$arr['graduation_time']);

        //     if (strpos($arr['graduation_time'], '-今')) {
        //         $arr['graduation_time'] = preg_replace("/-今/",'至今',$arr['graduation_time']);
        //     }
           
        // }
        $arr['educational_background'] = '';
        foreach ($list as $k => $v) {
            isset($v['graduation_time'])?$arr['educational_background'].=$v['graduation_time']."  ":$arr['educational_background'].='无法识别或缺少时间'.'   ';
            isset($v['school'])?$arr['educational_background'].=$v['school']."  ":$arr['educational_background'].='无法识别或缺少学校'.'   ';
            isset($v['speciality'])?$arr['educational_background'].=$v['speciality']."  ":$arr['educational_background'].='无法识别或缺少专业'.'   ';
            isset($v['educational'])?$arr['educational_background'].=$v['educational']."\n":$arr['educational_background'].='无法识别或缺少学历'."\n";
        }
        // $arr['educational_background'] = implode("\n", $list);
        // dump($arr);

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

    public function atSchool($parm){
        //在校情况
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
        unset($list['train']);
        unset($list['language']);
        unset($list['atSchool']);
        //处理毕业时间
        if (isset($list['graduation_time'])) {
            if ($list['graduation_time'] != '') {
                $list['graduation_time'] = str_replace('—','-',$list['graduation_time']);//处理特殊符号
                $graduation_time = explode('-',$list['graduation_time']);
                if (count($graduation_time) > 1) {

                    preg_match("/\d{4}/", $graduation_time[1],$res);
                    if(isset($res[0])){
                        $list['graduation_time'] = $res[0];
                    }
                    else{
                        $list['graduation_time'] = '无学历';
                    }
                    
                }
                else{
                    preg_match("/\d{4}/", $graduation_time[0],$res);
                    if (isset($res[0])) {
                        $list['graduation_time'] = $res[0];
                    }
                    else{
                        $list['graduation_time'] = '无学历';
                    }
                    
                }
            }
        }

        $company_config = config('config.workExperience.company');
        //误匹配公司成专业的情况，需要去掉
        if (isset($list['speciality']) && preg_match($company_config, $list['speciality'])) {
            unset($list['speciality']);
        }

        if (!isset($list['educational_background'])) {
            //匹配不到教育背景的时候
            $list['educational_background'] = '无法识别或缺少时间   ';
            $list['educational_background'].= isset($list['school'])?$list['school']."   ":'无法识别或缺少学校   ';
            $list['educational_background'].= isset($list['speciality'])?$list['speciality']."   ":'无法识别或缺少专业   ';
            $list['educational_background'].= isset($list['educational'])?$list['educational']."   ":'无法识别或缺少学历   ';
        }
        

        return json(['code' => 0,'msg' => '解析成功','data' => $list]);

    }

    public function trimall($str){
        $qian = array(" ","　","\t","\n","\r");
        return str_replace($qian, '', $str);  
    }

    public function importResume(){
        //导入简历
        $delete_id = input('post.deleteID');
        $file = request()->file('excelFile');
        $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/';
        $info = $file->validate(['size'=>20971520,'ext'=>'xlsx,xls'])->move($path);
        if($info){
            $delete_id = explode(",",$delete_id);
            $file = $path.$info->getSaveName();//获取路径
            $data = $this->readResume($file,$delete_id);
            if (empty($data)) {
                return json(['msg' => '没有数据','code' => 2]);
            }
            if (is_string($data)) {
                return json(['msg' => $data,'code' => 3]);
            }
            unset($info);//一定要unset之后才能进行删除操作，否则请求会被拒绝
            @unlink($file);
            
            foreach ($delete_id as $k => $v) {
                unset($data['resume'][$v]);

            }
            $resume = new ResumeModel();
            $communicate = new Communicate();
            // $row_id = $resume->max('row_id');
            // $row_id++;//取最大rowid标识
            $count_resume = count($data['resume']);
            $num = intval($count_resume/100);
            set_time_limit(0);

            Db::startTrans();//开始事务

            for ($i=0; $i <= $num; $i++) { 
                $insert_data = array_slice($data['resume'],$i*100,100);
                // $insert_comm = array_slice($data['communicate'],$i*200,200);
                $resume_res = $resume->insertAll($insert_data);
                if (!$resume_res) {
                    Db::rollback();//出错回滚
                    return json(['msg' => '导入失败，请重试','code' => 500]);
                }
                // $communicate->insertAll($insert_comm);
            }
            $resume_length = count($data['resume'])-1;
            $resume_ids = $resume->where('batch_id','=',$data['resume'][$resume_length]['batch_id'])->field('id')->order('id asc')->select()->toArray();
            $resume_ids = array_column($resume_ids,'id');
            $insert_comm = [];
            $keys = 0;
            $insert_c = 0;//写入频率
            $length = count($resume_ids)-1;
            foreach ($resume_ids as $key => $value) {
                if(isset($data['communicate'][$key]['content1'])){
                    if (!empty($data['communicate'][$key]['content1'])) {
                        $insert_comm[$keys]['ct_user'] = $data['communicate'][$key]['ct_user1'];
                        $insert_comm[$keys]['communicate_time'] = $data['communicate'][$key]['communicate_time1'];
                        $insert_comm[$keys]['content'] = $data['communicate'][$key]['content1'];
                        $insert_comm[$keys]['resume_id'] = $value;

                        /*******************************暂不开放对沟通详细情况的导入**************************************/
                        // $insert_comm[$keys]['screen'] = 0;
                        // $insert_comm[$keys]['arrange_interview'] = 0;
                        // $insert_comm[$keys]['arrive'] = 0;
                        // $insert_comm[$keys]['approved_interview'] = 0;
                        // $insert_comm[$keys]['entry'] = 0;
                        /*********************************************************************************************/
                        $keys++;
                    }

                }
                if(isset($data['communicate'][$key]['content2'])){
                    if (!empty($data['communicate'][$key]['content2'])) {
                        $insert_comm[$keys]['ct_user'] = $data['communicate'][$key]['ct_user2'];
                        $insert_comm[$keys]['communicate_time'] = $data['communicate'][$key]['communicate_time2'];
                        $insert_comm[$keys]['content'] = $data['communicate'][$key]['content2'];
                        $insert_comm[$keys]['resume_id'] = $value;

                        /*******************************暂不开放对沟通详细情况的导入**************************************/
                        // $insert_comm[$keys]['screen'] = $data['communicate'][$key]['screen'];
                        // $insert_comm[$keys]['arrange_interview'] = $data['communicate'][$key]['arrange_interview'];
                        // $insert_comm[$keys]['arrive'] = $data['communicate'][$key]['arrive'];
                        // $insert_comm[$keys]['approved_interview'] = $data['communicate'][$key]['approved_interview'];
                        // $insert_comm[$keys]['entry'] = $data['communicate'][$key]['entry'];
                        /**********************************************************************************************/
                        $keys++;
                    }

                }
                /*******************************暂不开放对沟通详细情况的导入**************************************/
                // else if(isset($data['communicate'][$key]['content1'])){
                //     if (!empty($data['communicate'][$key]['content1'])) {
                //         $insert_comm[$keys-1]['screen'] = $data['communicate'][$key]['screen'];
                //         $insert_comm[$keys-1]['arrange_interview'] = $data['communicate'][$key]['arrange_interview'];
                //         $insert_comm[$keys-1]['arrive'] = $data['communicate'][$key]['arrive'];
                //         $insert_comm[$keys-1]['approved_interview'] = $data['communicate'][$key]['approved_interview'];
                //         $insert_comm[$keys-1]['entry'] = $data['communicate'][$key]['entry'];
                //     }
                // }
                /***************************************************************************************************/


                if ($insert_c == 200 && $key != $length) {
                    $communicate_res = $communicate->insertAll($insert_comm);
                    if (!$communicate_res) {
                        Db::rollback();//回滚
                        return json(['msg' => '导入失败，请重试','code' => 500]);
                    }
                    $insert_comm = [];
                    $insert_c = 0;
                }
                if ($key == $length) {

                    $res = $communicate->insertAll($insert_comm);
                    // $res = $resume->where(['row_id' => $data['resume'][2]['row_id']])->limit(1)->delete();
                    if ($res) {
                        Db::commit();//提交
                        return json(['msg' => '批量导入完成','code' => 0]);
                    }
                    else{
                        Db::rollback();//回滚
                        return json(['msg' => '导入失败，请重试','code' => 500]);
                    }
                }

                $insert_c++;
            }

            // $res = $resume->where(['row_id' => $data['resume'][2]['row_id']])->limit(1)->delete();

            // $communicate->insertAll($insert_comm);

            // Db::execute('update rs_communicate set resume_id = (select id from rs_resume where rs_communicate.row_id = rs_resume.row_id and rs_communicate.resume_id = 0 )');
            


            // $last_num = $count_resume-$num*500;
            // $insert_data = array_slice($data['resume'],500*$num,$last_num);
            // $res_resume = $resume->insertAll($data['resume']);
            // $res_comm = $communicate->insertAll($data['communicate']);
            // if ($res_resume && $res_comm) {
                
            // }
            return json(['msg' => '批量导入失败','code' => 3]);
        }else{
            // 上传失败获取错误信息
            return json(['msg' => $file->getError(),'code' => 1]);
        }
        
        // $data = input('post.data');
        // dump($data);exit;
        // $personal_name = array_column($data,'personal_name');
        // $phone = array_column($data,'phone');
        // $check_data = '';
        // $resume = new ResumeModel();
        // $user = new User();
        // $check = [];


        // foreach ($data as $k => $v) {
        //     $name = $resume->getUname(['phone' => $v['phone']]);
        //     if ($name) {
        //         $check_data.='姓名:'.$name.',电话:'.$v['phone'].'已存在';
        //     }

        //     $temp = $data[$k]['name'].$data[$k]['phone'];
        //     if (in_array($temp,$check)) {
        //         return json(['msg' => '检测到重复数据，请检查','code' => 2,'data' => []]);
        //     }
        //     $ct_user = $user->getUser(['personal_name' => $v['personal_name']]);
        //     if ($ct_user == '') {
        //         $data[$k]['ct_user'] = Session::get('user_info')['uname'];
        //     }
        //     else{
        //         $data[$k]['ct_user'] = $ct_user;
        //     }
        //     unset($data[$k]['personal_name']);

        //     if(isset($v['work_year'])){
        //         $data[$k]['work_year'] = (int)$data[$k]['work_year'];
        //     }
        //     if (isset($v['age'])) {
        //         $data[$k]['age'] = (int)$data[$k]['age'];
        //     }

        //     if (isset($v['expected_money'])) {
        //         $money = preg_replace("/(到|至)/",'-',$v['expected_money']);
        //         $money = explode('-',$v['expected_money']);

        //         if (count($money) > 1) {
        //             $data[$k]['expected_money_start'] = (int)$money[0];
        //             $data[$k]['expected_money_end'] = (int)$money[1];
        //         }
        //         else{
        //             $data[$k]['expected_money_start'] = (int)$money[0];
        //             $data[$k]['expected_money_end'] = (int)$money[0];
        //         }
        //     }

        // }

        // if ($check_data != '') {
        //     return json(['msg' => $check_data,'code' => 3,'data' => []]);
        // }
        
        // // dump($data);exit;

        // $res = $resume->addAll($data);

        // if ($res) {
        //     return json(['msg' => '添加成功','code' => 0,'data' => []]);
        // }
        // else{
        //     return json(['msg' => '添加失败','code' => 1,'data' => []]);
        // }

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
            $data = $this->readResume($file,false);
            if (empty($data)) {
                return json(['msg' => '没有数据','code' => 2]);
            }
            unset($info);//一定要unset之后才能进行删除操作，否则请求会被拒绝
            @unlink($file);
            if (is_string($data)) {
                $msg = $data;
                $code = 2;
                $data = '';
            }
            else{
                $msg = '上传成功';
                $data = array_merge($data);
                $code = 0;
            }
            return json(['msg' => $msg,'code' => $code,'data' => $data ]);
        }else{
            // 上传失败获取错误信息
            return json(['msg' => $file->getError(),'code' => 1]);
        }
    }

    public function readResume($file,$deleteID = true){
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
        $communicate = [];//沟通
        $user = new User();
        $keys = 0;//沟通数据下标
        $source = ['前程无忧','中国人才','智联招聘','拉勾网','BOSS直聘','校园招聘','内部推荐','外部推荐','协调转入','实习憎','自动投递','返聘','其他渠道'];
        $ct_user = $user->field('uname')->select()->toArray();//创建人
        $ct_user = array_column($ct_user,'uname');
        if ($deleteID == true) {
            $resume = new ResumeModel();
            $row_id = time().Session::get('user_info')['uname'];//生成唯一标识符，进行批量导入标识
        }
        
        // $resume_max_id = $resume->max('id');//获取最大id
        // $resume_max_id = $resume_max_id + 1;//最大+1为简历起始id
        
        // $personal_name = '';//真名
        for($row = 2;$row <= $total;$row++){
            if ($deleteID != false) {
                if (in_array($row,$deleteID)) {
                    //跳过不需要的行，进行导入
                    continue;
                }
            }
            $a = $obj_excel -> getActiveSheet() -> getCell("A".$row)->getValue();//招聘负责人姓名
            $b = $obj_excel -> getActiveSheet() -> getCell("B".$row)->getValue();//日期
            $c = $obj_excel -> getActiveSheet() -> getCell("C".$row)->getValue();//岗位
            $d = $obj_excel -> getActiveSheet() -> getCell("D".$row)->getValue();//候选人
            $e = $obj_excel -> getActiveSheet() -> getCell("E".$row)->getValue();//联系电话
            $f = $obj_excel -> getActiveSheet() -> getCell("F".$row)->getValue();//邮件
            $g = $obj_excel -> getActiveSheet() -> getCell("G".$row)->getValue();//毕业学校
            $h = $obj_excel -> getActiveSheet() -> getCell("H".$row)->getValue();//学历
            $i = $obj_excel -> getActiveSheet() -> getCell("I".$row)->getValue();//毕业年份
            $j = $obj_excel -> getActiveSheet() -> getCell("J".$row)->getValue();//工作年限
            $k = $obj_excel -> getActiveSheet() -> getCell("K".$row)->getValue();//简历来源
            $l = $obj_excel -> getActiveSheet() -> getCell("L".$row)->getValue();//公司类型
            $m = $obj_excel -> getActiveSheet() -> getCell("M".$row)->getValue();//首次沟通
            $n = $obj_excel -> getActiveSheet() -> getCell("N".$row)->getValue();//二次沟通

            /*******************************暂不开放对沟通详细情况的导入**************************************/
            // $o = $obj_excel -> getActiveSheet() -> getCell("O".$row)->getValue();//推荐
            // $p = $obj_excel -> getActiveSheet() -> getCell("P".$row)->getValue();//安排
            // $q = $obj_excel -> getActiveSheet() -> getCell("Q".$row)->getValue();//到场
            // $r = $obj_excel -> getActiveSheet() -> getCell("R".$row)->getValue();//通过
            // $s = $obj_excel -> getActiveSheet() -> getCell("S".$row)->getValue();//入职
            /**********************************************************************************************/


            if (empty($a) && empty($b) && empty($c) && empty($d) && empty($e) && empty($f) && empty($g) && empty($h) && empty($i) && empty($j) && empty($k) && empty($l) && empty($m) && empty($n)) {
                continue;//无效数据跳过
            }

            if (!in_array($a,$ct_user) && $total < $row) {
                return '检测到不存在用户，请检查第'.$row.'行';
            }
            
            if (empty($b)) {
                return '检测到缺少日期，请检查第'.$row.'行';
            }

            if (empty($c)) {
                return '检测到缺少岗位，请检查第'.$row.'行';
            }

            if (empty($d)) {
                return '检测到缺少候选人，请检查第'.$row.'行';
            }

            if (empty($e)) {
                return '检测到缺少联系电话，请检查第'.$row.'行';
            }

            if (empty($f)) {
                return '检测到缺少邮件，请检查第'.$row.'行';
            }

            if (empty($g)) {
                return '检测到缺少毕业学校，请检查第'.$row.'行';
            }

            if (empty($h)) {
                return '检测到缺少学历，请检查第'.$row.'行';
            }

            if (empty($i)) {
                return '检测到缺少毕业年份，请检查第'.$row.'行';
            }

            if (empty($j)) {
                return '检测到缺少工作年限，请检查第'.$row.'行';
            }

            if (empty($k)) {
                return '检测到缺少简历来源，请检查第'.$row.'行';
            }

            if (empty($l)) {
                return '检测到缺少公司类型(大展/同和)，请检查第'.$row.'行';
            }

            if ($deleteID == false) {
                $data[$row]['personal_name'] = $a;
            }
            else{
                // if ($obj_excel -> getActiveSheet() -> getCell("A".$i)->getValue() != $personal_name) {
                //     $ct_user = $user->where('personal_name','=',$obj_excel -> getActiveSheet() -> getCell("A".$i)->getValue())->value('uname');
                //     $personal_name = $obj_excel -> getActiveSheet() -> getCell("A".$i)->getValue();
                // }
                
                $data[$row]['ct_user'] = $a;
            }
            
            if ($b != '' && is_numeric($b)) {
                $time = ($b-25569)*24*60*60; //获得秒数
                $data[$row]['ct_time'] = date('Y-m-d', $time);   //转化时间
            }
            else if ($b != '') {
                $data[$row]['ct_time'] = $b;
            }
            else{
                $data[$row]['ct_time'] = '';
            }
            $deleteID == false?$data[$row]['row_id'] = $row:'';
            $data[$row]['expected_job'] = $c;
            $data[$row]['name'] = $d;
            $data[$row]['phone'] = $e;
            $data[$row]['email'] = $f;
            $data[$row]['school'] = $g;
            $data[$row]['educational'] = $h;
            $data[$row]['graduation_time'] = $i;
            $data[$row]['work_year'] = $j;

            if (!in_array($k,$source)) {
                return '检测到不存在的简历来源，请检查第'.$row.'行';
            }
            $data[$row]['source'] = $k;
            if ($deleteID == false) {
                $data[$row]['custom1'] = $m;
                $data[$row]['custom2'] = $n;
            }
            else{

                // if ($obj_excel -> getActiveSheet() -> getCell("M".$i)->getValue() != '') {
                    $communicate[$keys]['ct_user1'] = $data[$row]['ct_user'];
                    $communicate[$keys]['communicate_time1'] = $data[$row]['ct_time'];
                    // $communicate[$keys]['resume_id'] = $resume_max_id;
                    $communicate[$keys]['row_id'] = $i;
                    $communicate[$keys]['content1'] =  $m;
                    // $keys++;
                // }
                // if ($obj_excel -> getActiveSheet() -> getCell("N".$i)->getValue() != '') {
                    $communicate[$keys]['ct_user2'] = $data[$row]['ct_user'];
                    $communicate[$keys]['communicate_time2'] = $data[$row]['ct_time'];
                    // $communicate[$keys]['resume_id'] = $resume_max_id;
                    $communicate[$keys]['row_id'] = $i;
                    $communicate[$keys]['content2'] =  $n;
                    // $keys++;
                    /*******************************暂不开放对沟通详细情况的导入**************************************/
                    // $communicate[$keys]['screen'] = $o == ''?0:1;//沟通情况，通过
                    // $communicate[$keys]['arrange_interview'] = $p == ''?0:1;//安排
                    // $communicate[$keys]['arrive'] = $q == ''?0:1;//到场
                    // $communicate[$keys]['approved_interview'] = $r == ''?0:1;//通过
                    // $communicate[$keys]['entry'] = $s == ''?0:1;//入职
                    /***********************************************************************************************/

                // }
                $keys++;
                // $data[$i]['id'] = $resume_max_id;
                // $resume_max_id++;
                // $data[$i]['row_id'] = $i;
                $data[$row]['batch_id'] = $row_id;

            }
            $data[$row]['custom1'] = $m;
            $data[$row]['custom2'] = $n;

            $data[$row]['company_type'] = $l;

        }
        if ($deleteID == false) {
            return $data;
        }
        else{
            return ['resume' => $data,'communicate' => $communicate];
        }

    }

    public function upload(){
        //上传
        $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/';
        // 获取表单上传文件
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>209715200,'ext'=>'xls,xlsx,doc,docx,html,htm,mht,pdf,jpg,jpeg,png'])->move($path);
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
        $data = $this->search($input);
        $count = array_pop($data);//sphinx获取的总条数设置上限为10000条，可能导致某些结果不准确
        if (count($input) == 3 ) {
            $resume = new ResumeModel();
            // $data = $resume->get();
            $count = $resume->getCount();//
        }
        
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
        $res = $resume->getOne(['name' => $data['name'],'phone' => $data['phone'],'email' => $data['email']]);
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
            if (preg_match("/\d+(\.\d+)?/",$data['work_year'],$matches)) {
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

        if (isset($data['educational_background'])) {
            if (count(explode('   ',$data['educational_background'])) <= 5) {
                if (isset($data['graduation_time'])) {
                    if(preg_match("/\d{4}/",$data['graduation_time'],$res)){
                        $graduation_time = $res[0].'年毕业';
                        $data['educational_background'] = preg_replace("/无法识别或缺少时间/",$graduation_time,$data['educational_background']);

                    }
                }
               
                $school = isset($data['school'])?$data['school']:'缺少毕业院校';
                $data['educational_background'] = preg_replace("/无法识别或缺少学校/",$school,$data['educational_background']);

                $speciality = isset($data['speciality'])?$data['speciality']:'缺少专业';
                $data['educational_background'] = preg_replace("/无法识别或缺少专业/",$speciality,$data['educational_background']);

                $educational = isset($data['educational'])?$data['educational']:'缺少学历';
                $data['educational_background'] = preg_replace("/无法识别或缺少学历/",$educational,$data['educational_background']);

            }

        }

        if (isset($data['speciality'])) {
            //追加新的专业
            $speciality_arr = explode('|',file_get_contents(dirname(Env::get('ROOT_PATH')).'/server/extend/speciality.txt'));
            if (!in_array($data['speciality'],$speciality_arr)) {
                file_put_contents(dirname(Env::get('ROOT_PATH')).'/server/extend/speciality.txt','|'.$data['speciality'],FILE_APPEND);

            }
        }
        $data['ct_timestamp'] = time();

        $id = $resume->add($data);
        $data['id'] = $id;
        $data['ct_time'] = date('Y-m-d H:i:s',time());
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
            if (preg_match("/\d+(\.\d+)?/",$data['work_year'],$matches)) {
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

        if (Session::get('user_info')['id'] == 1) {
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
        else{
            $ct_user = $resume->where('id','=',$id)->value('ct_user');
            if ($ct_user != Session::get('user_info')['uname']) {
                return json(['msg' => '没有权限删除别人的简历','code' => 500]);
            }
            else{
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
        }

        
    }

    public function delFile(){
        //删除简历文件
        $id = input('id');
        if (empty($id)) {
            return json(['msg' => 'id字段不存在','code' => 2]);
        }
        $upload = new ResumeUpload();

        if (Session::get('user_info')['id'] == 1) {
            $res = $upload->del(['id' => $id]);
        }
        else{
            $ct_user = $upload->where('id','=',$id)->value('ct_user');
            if ($ct_user != Session::get('user_info')['uname']) {
                return json(['msg' => '没有权限删除别人的上传文件','code' => 500]);
            }
            else{
                $res = $upload->del(['id' => $id]);
            }
        }
        
        
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
        Header("Content-Disposition: attachment;filename='$parm[file_name]'"); 
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
        // $source = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/file/test.docx';
        // $text = new \Docx2Text();
        // $text->setDocx($source);
        // $docx = $text->extract();
        // dump($docx);
        // $obj = new \Analysis();

        // $start1=microtime(true);

        // $content = $obj->getContent($source,'string');
        // $phpWord = IOFactory::load($source);
        // dump($phpWord);
        // $source = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/file/test.pdf';
        // PDF_open_file($pdf,$source);
        $where = input('get.');
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
        // $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //匹配模式 
        $sphinx->setMatchMode(SPH_MATCH_BOOLEAN);   //匹配模式 
        // $sphinx->setMatchMode(SPH_MATCH_PHRASE);   //匹配模式 
        //ANY为关键词自动拆词，ALL为不拆词匹配（完全匹配），EXTENDED2,多词匹配
        $sphinx->SetArrayResult ( true );   //返回的结果集为数组
        // $sphinx->SetLimits(0 , 100000000 , 6000);
        $sphinx->SetLimits(($where['pageIndex'] - 1) * $where['pageSize'] , $where['pageSize'] , 3000);//分页

        //**********************************先不要的条件去掉**********************************************/

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

        // $age_min = isset($where['age_min'])?$where['age_min']:'';
        // $age_max = isset($where['age_max'])?$where['age_max']:'';
        // if ($age_min && $age_max) {
        //     $sphinx->SetFilterRange('age', $age_min, $age_max);//查找年龄最小-最大之间
        // }else if($age_min && !$age_max){    //年龄
        //     $sphinx->SetFilterRange('age', $age_min, 100);//查找年龄最小-100之间
        // }else if(!$age_min && $age_max){
        //     $sphinx->SetFilterRange('age', 0, $age_max);//查找年龄0-最大之间
        // }else{
        //     // $arr_ids[] = [];
        // }

        /*************************************************************************************************/
        $ct_time = isset($where['ct_time'])?$where['ct_time']:'';
        if ($ct_time) {
            $min_time = strtotime($ct_time.' 00:00:00');
            $max_time = strtotime($ct_time.' 23:59:59');
            $sphinx->SetFilterRange('ct_timestamp',$min_time,$max_time);
        }

        // if ($ct_time) {
        //     $data = $resume->getId("ct_time between '$ct_time 00:00:00' and '$ct_time 23:59:59'");
        //     //说明有结果
        //     if ($data) {
        //         //取id结果集合
        //         $ids = array_column($data,'id');
        //         dump($ids);exit;
        //         $sphinx->SetFilter('attachment',$ids);
        //     }
        //     else{
        //         //没有结果集，说明所选的日期没有数据
        //         return [];
        //     }
            
        // }


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
        $arr['ct_time'] = isset($where['ct_time'])?'('.$where['ct_time'].')':'';
        // $arr['status'] = isset($where['status'])?$where['status']:'';
        // $arr['school'] = isset($where['school'])?$where['school']:'';
        // $arr['speciality'] = isset($where['speciality'])?$where['speciality']:'';
        // $arr['english'] = isset($where['english'])?$where['english']:'';
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

        $ct_user = isset($where['ct_user'])?$where['ct_user']:'';
        if ($ct_user) {
            $ct_user = preg_replace("/(,|，)/",',',$ct_user);
            $ct_user = explode(",",$ct_user);
            $ct_user_where = '';
            $ct_user_length = count($ct_user)-1;
            foreach ($ct_user as $k => $v) {
                if ($k == $ct_user_length) {
                    $ct_user_where.=$ct_user_where."@ct_user $v";
                }
                else{
                    $ct_user_where.=$ct_user_where."@ct_user $v |";
                }
                
            }
            $phinx_where = empty($phinx_where)?$ct_user_where:$phinx_where.' & '.$ct_user_where;
            
            // $sphinx->AddQuery($other,'resume');
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
        $sphinx->SetSortMode(SPH_SORT_ATTR_DESC,'mfy_time');
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
        $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);   //匹配模式 
        // $sphinx->setMatchMode(SPH_MATCH_BOOLEAN);   //匹配模式 
        // $sphinx->setMatchMode(SPH_MATCH_PHRASE);   //匹配模式 
        //ANY为关键词自动拆词，ALL为不拆词匹配（完全匹配），EXTENDED2,多词匹配
        $sphinx->SetArrayResult ( true );   //返回的结果集为数组
        // $sphinx->SetLimits(0 , 100000000 , 6000);
        $sphinx->SetLimits(($where['pageIndex'] - 1) * $where['pageSize'] , $where['pageSize'] , 3000);//分页

        //**********************************先不要的条件去掉**********************************************/

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

        // $age_min = isset($where['age_min'])?$where['age_min']:'';
        // $age_max = isset($where['age_max'])?$where['age_max']:'';
        // if ($age_min && $age_max) {
        //     $sphinx->SetFilterRange('age', $age_min, $age_max);//查找年龄最小-最大之间
        // }else if($age_min && !$age_max){    //年龄
        //     $sphinx->SetFilterRange('age', $age_min, 100);//查找年龄最小-100之间
        // }else if(!$age_min && $age_max){
        //     $sphinx->SetFilterRange('age', 0, $age_max);//查找年龄0-最大之间
        // }else{
        //     // $arr_ids[] = [];
        // }

        /*************************************************************************************************/

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

        // $ct_time = isset($where['ct_time'])?$where['ct_time']:'';
        // if ($ct_time != '') {
        //     $sphinx->SetFilterRange('ct_time',$ct_time.' 00:00:00',$ct_time.' 23:59:59');
        // }


        $arr = [];
        $arr['name'] = isset($where['name'])?$where['name']:'';
        $arr['sex'] = isset($where['sex'])?$where['sex']:'';
        $arr['educational'] = isset($where['educational'])?$where['educational']:'';
        $arr['phone'] = isset($where['phone'])?$where['phone']:'';
        $arr['expected_job'] = isset($where['expected_job'])?$where['expected_job']:'';
        // $arr['status'] = isset($where['status'])?$where['status']:'';
        // $arr['school'] = isset($where['school'])?$where['school']:'';
        // $arr['speciality'] = isset($where['speciality'])?$where['speciality']:'';
        // $arr['english'] = isset($where['english'])?$where['english']:'';
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

        $ct_user = isset($where['ct_user'])?$where['ct_user']:'';
        if ($ct_user) {
            $ct_user = preg_replace("/(,|，)/",',',$ct_user);
            $ct_user = explode(",",$ct_user);
            $ct_user_where = '';
            $ct_user_length = count($ct_user)-1;
            foreach ($ct_user as $k => $v) {
                if ($k == $ct_user_length) {
                    $ct_user_where.=$ct_user_where."@ct_user $v";
                }
                else{
                    $ct_user_where.=$ct_user_where."@ct_user $v |";
                }
                
            }
            $phinx_where = empty($phinx_where)?$ct_user_where:$phinx_where.' & '.$ct_user_where;
            
            // $sphinx->AddQuery($other,'resume');
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
        $sphinx->SetSortMode(SPH_SORT_ATTR_DESC,'mfy_time');
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
            // $data[] = count($data_arr);//总数

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
        if (Session::get('user_info')['id'] == 1) {
            //超级管理员不做任何验证删除
            $res = $job_model->where('id','=',$id)->delete();
        }
        else{
            $ct_user = $job_model->where('id','=',$id)->value('ct_user');
            if ($ct_user != Session::get('user_info')['uname']) {
                return json(['msg' => '没有权限删除别人的数据','code' => 500,'data' => []]);
            }
            else{
                $res = $job_model->where('id','=',$id)->delete();
            }
        }
        
        if ($res) {
            return json(['msg' => '删除成功','code' => 0,'data' => []]);
        }

    }

    public function export(){
        //简历导出
        $PHPWord = new \PhpOffice\PhpWord\PhpWord();
        // $section = $PHPWord->createSection();
        $option = function($msg){
            if ($msg == 'tonghe') {
                //同和
                return ['source' => dirname(Env::get('ROOT_PATH')).'/client/dist/template/'.'tonghe.docx','file_type' => '同和'];
            }
            else if ($msg == 'avo') {
                //大展
                return ['source' => dirname(Env::get('ROOT_PATH')).'/client/dist/template/'.'avo.docx','file_type' => '大展'];
            }
            else{
                //缺少类型或者简历id
                echo json_encode(['msg' => $msg,'code' => 1]);exit;
            }
        };
        $resume_id = input('get.resume_id');
        $type = input('get.type');
        $resume_id == ''?$option('缺少简历ID'):'';
        $result = $type == ''?$option('缺少类型'):$option($type);
        $dest = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/file/'.time().Session::get('user_info')['uname'].'.docx';
        copy($result['source'], $dest);//复制一份

        $resume = new ResumeModel();
        $data = $resume->where('id','=',$resume_id)->find();//获取数据


        $templateProcessor = new TemplateProcessor($dest);//打开模板

        $templateProcessor->setValue('name',$data['name']);//候选人
        $templateProcessor->setValue('job',$data['expected_job']);//岗位
        $templateProcessor->setValue('sex',$data['sex']);//性别
        $templateProcessor->setValue('birthday',$data['birthday']);//生日
        $templateProcessor->setValue('work_year',$data['work_year']);//工作年限

        //自我评价
        $self_evaluation = explode("\n",$data['selfEvaluation']);
        $self = new TextRun();
        foreach ($self_evaluation as $k => $v) {
            if ($v == '') {
                continue;
            }
            $self->addText(htmlspecialchars($v),['size' => 10]);
            $self->addTextBreak(1);
        }
        $templateProcessor->setComplexBlock('self_evaluation',$self);

        /**************************************************方法1********************************************/
        $work_experience = explode("\n",$data['workExperience']);
        $group_list = config('config.group_list');//标题集合，去除标题
        $work_rule = config('config.workExperience');//工作经验的匹配集合
        $work = new TextRun();
        foreach ($work_experience as $k => $v) {
            if ($v == '' || in_array(trim($v),$group_list)) {
                continue;
            }
            $v = preg_replace("/\||￥/",'',$v);//处理特殊符号
            if (preg_match($work_rule['time_total'],$v)) {
                $v = preg_replace("/\s+/",'',$v);
            }
            if(preg_match("/\d+(\s+)(至|-|—|–|―|到)+(\s+)(\d+|至今)/",$v,$preg)){//处理时间格式，空格问题
                $v = preg_replace("/$preg[0]/",preg_replace("/\s+/",'',$preg[0]),$v);
            }
            $v = preg_replace($work_rule['time_total'],'',$v);//处理时间
            $v = preg_replace($work_rule['money'],'',$v);//处理钱
            // if (preg_match($work_rule['industry'],$v)) {
            //     continue;
            // }

            if (preg_match("/(工作描述:|工作描述：)(\s+)?/",$v,$preg)) {//处理个别空格内容换行问题，主要是内容
                $v = preg_replace("/\s+/",'',$v);
                $v = preg_replace("/(工作描述:|工作描述：)/",'工作描述: ',$v);
                $content = explode(' ',$v);
                foreach ($content as $k => $v) {
                    if (empty($v)) {
                        continue;
                    }
                    $work->addText(htmlspecialchars($v),['size' => 10]);
                    $work->addTextBreak(1);
                }
                continue;
            }
            
            
            $work->addText(htmlspecialchars($v),['size' => 10]);
            $work->addTextBreak(1);
        }
        $templateProcessor->setComplexBlock('work_experience',$work);

        /***************************************************************************************************/

        /*************************************方法2备份*****************************************************/
        //工作经验
        /*$work_experience = explode("\n",$data['workExperience']);
        // foreach ($work_experience as $k => $v) {
        //     if(preg_match("/\d+(\s+)?(-|至|到)(\s+)?(\d+|至今)/",$v,$preg)){
        //         $work_experience[$k] = preg_replace("/$preg[0]/",preg_replace("/\s+/",'',$preg[0]),$v);
        //     }
        //     //处理时间空格问题
        // }

        // $work_experience = implode("\n",$work_experience);
        // $work_experience = str_replace("\n",' ',$work_experience);//去换行
        // $work_experience = preg_replace("/\s+/",' ',$work_experience);//去空格
        
        // $work_experience = explode(' ',$work_experience);
        // dump($work_experience);exit;
 
        $group_list = config('config.group_list');//标题集合，去除标题
        $work_rule = config('config.workExperience');//工作经验的匹配集合
        $work = new TextRun();
        $preg_array = ['company','work_time'];//匹配时间和公司名字
        $list = [];//结果集
        $keys = 0;//下标
        $max_company_length = 0;
        $max_time_length = 0;
        foreach ($work_experience as $k => $v) {
            if ($v == '' || in_array(trim($v),$group_list)) {
                continue;
            }
            $v = preg_replace("/\|/",'',$v);//处理特殊符号
            if (preg_match($work_rule['time_total'],$v)) {
                $v = preg_replace("/\s+/",'',$v);
            }
            if(preg_match("/\d+(\s+)(-|至|到)(\s+)(\d+|至今)/",$v,$preg)){//处理时间格式，空格问题
                $v = preg_replace("/$preg[0]/",preg_replace("/\s+/",'',$preg[0]),$v);
            }
            $v = preg_replace($work_rule['time_total'],'',$v);//处理时间
            $v = preg_replace($work_rule['money'],'',$v);//处理钱

            if (empty($preg_array) && preg_match($work_rule['work_time'],$v,$preg) || empty($preg_array) && preg_match($work_rule['company'],$v,$preg)) {
                $list[$keys]['job'] = $data['expected_job'];
                $keys++;
                $preg_array = ['company','work_time'];//匹配时间和公司名字
            }
            //处理时间空格问题
            if (preg_match($work_rule['work_time'],$v,$preg)) {
                // if (preg_match("/至今/",$preg[0])) {
                //     if (isset($list[0]['work_time'])) {
                //         continue;//匹配到至今的字段将跳过
                //     }
                // }
                if ($keys > 1) {
                    if(strtotime($preg[0]) > strtotime($list[$keys-1]['work_time'])){
                        continue;//时间错误跳过
                    }
                }
                
                if (in_array('work_time',$preg_array)) {
                    $max_time_length = $max_time_length >= strlen($preg[0])?$max_time_length:strlen($preg[0]);
                    $list[$keys]['work_time'] = $preg[0];//工作经历的时间
                    $v = str_replace($preg[0],'',$v);
                    array_pop($preg_array);//匹配成功之后弹出
                }

            }
            if (preg_match($work_rule['company'],$v,$preg)) {
                if (preg_match("/上市公司|创业公司/",$preg[0])) {
                    continue;
                }
                if (in_array('company',$preg_array)) {
                    $max_company_length = $max_company_length >= strlen($preg[0])?$max_company_length:strlen($preg[0]);
                    $list[$keys]['company'] = $preg[0];//工作经历的时间
                    $v = str_replace($preg[0],'',$v);
                    array_shift($preg_array);//匹配成功后弹出
                }

            }
            // if (empty($preg_array)) {
            //     !isset($list[$keys]['content'])?$list[$keys]['content'] = '':'';
            //     $list[$keys]['content'].= $v;
            // }

            // if (preg_match($work_rule['work_time'],$v,$preg)) {//标题加粗
            //     $work->addTextBreak(1);//加换行
            //     $v = preg_replace($work_rule['work_time'],$preg[0].'     ',$v);
            //     $work->addText(htmlspecialchars($v),['size' => 10,'bold' => true]);
            //     $work->addTextBreak(1);
            //     continue;
            // }
            // if (preg_match("/(:|：)\s+/",$v,$preg)) {//处理个别空格内容换行问题，主要是内容
            //     $v = preg_replace("/(:|：)\s+/",': ',$v);
            //     $content = explode(' ',$v);
            //     foreach ($content as $k => $v) {
            //         if (empty($v)) {
            //             continue;
            //         }
            //         $work->addText(htmlspecialchars($v),['size' => 10]);
            //         $work->addTextBreak(1);
            //     }
            //     continue;
            // }
            
            // $work->addText(htmlspecialchars($v),['size' => 10]);
            // $work->addTextBreak(1);
        }
        isset($list[$keys]['work_time'])&&isset($list[$keys]['company'])?$list[$keys]['job'] = $data['expected_job']:'';
        foreach ($list as $k => $v) {
            isset($v['work_time'])?$time = $v['work_time']:$time = '';
            isset($v['company'])?$company = $v['company']:$company = '';
            isset($v['job'])?$job = $v['job']:$job = '';
            $time = strlen($time) >= $max_time_length?$time:$time.str_repeat(' ',($max_time_length-strlen($time))/3*2); 
            $company = strlen($company) >= $max_company_length?$company:$company.str_repeat(' ',($max_company_length-strlen($company))/3*2);
            dump($company);
            if ($time == ''|| $company == '' || $job == '') {
                $error = '数据有异常，手动处理';
                $work->addText(htmlspecialchars($time.'     '.$company.'     '.$job),['size' => 10,'color' => 'red','bold' => true]);
                $work->addTextBreak(1);
                $work->addText($error,['size' => 10,'color' => 'red']);
                $work->addTextBreak(1);
                continue;
            }
            $work->addText(htmlspecialchars($time.'     '.$company.'     '.$job),['size' => 10,'bold' => true]);
            $work->addTextBreak(1);
        }
        $templateProcessor->setComplexBlock('work_experience',$work);
        */

        /****************************************************************************************************************/

        //项目经验
        $project_experience = explode("\n",$data['projectExperience']);
        $project_rule = config('config.projectExperience');
        $project = new TextRun();
        foreach ($project_experience as $k => $v) {
            if ($v == '' || in_array(trim($v),$group_list)) {
                continue;
            }
            if(preg_match("/\d+(\s+)?(至|-|—|–|―|到)+(\s+)?(\d+|至今)/",$v,$preg)){//处理时间格式，空格问题
                $v = preg_replace("/$preg[0]/",preg_replace("/\s+/",'',$preg[0]),$v);
            }
            if (preg_match($project_rule['project_time'],$v,$preg)) {//标题加粗
                $project->addTextBreak(1);//加换行
                $v = preg_replace($project_rule['project_time'],$preg[0].'     ',$v);
                $project->addText(htmlspecialchars($v),['size' => 10,'bold' => true]);
                $project->addTextBreak(1);
                continue;
            }
            if (preg_match("/(责任描述:|责任描述：|项目责任:|项目责任：|项目职责：|项目职责:)(\s+)?/",$v,$preg)) {//处理个别空格内容换行问题，主要是内容
                $v = preg_replace("/\s+/",'',$v);
                $v = preg_replace("/(责任描述:|责任描述：)/",'责任描述: ',$v);
                $content = explode(' ',$v);
                foreach ($content as $k => $v) {
                    if (empty($v)) {
                        continue;
                    }
                    $project->addText(htmlspecialchars($v),['size' => 10]);
                    $project->addTextBreak(1);
                }
                continue;
            }
            $project->addText(htmlspecialchars($v),['size' => 10]);
            $project->addTextBreak(1);
        }
        $templateProcessor->setComplexBlock('project_experience',$project);

        //教育背景部分
        $educational_background = $data['educational_background'];
        if(preg_match("/\d+(\s+)?(至|-|—|–|―|到)+(\s+)?(\d+|至今)/",$data['educational_background'],$preg)){//处理时间格式，空格问题
            $educational_background = preg_replace("/$preg[0]/",preg_replace("/\s+/",'',$preg[0]),$educational_background);
        }
        $educational_background = explode("\n", preg_replace("/\s+/","\n",$educational_background));
        $section = new TextRun();
        $edu_config = config('config.educationalBackground');
        $edu = [];
        $edu_key = 0;
        $max_time_length = 0;
        $max_school_length = 0;
        $max_speciality_length = 0;
        $max_educational_length = 0;
        $error = '';//警告
        $color = 'black';//字体颜色

        foreach ($educational_background as $k => $v) {
            
            if(preg_match($edu_config['graduation_time'],$v,$preg)){
                if (isset($edu[$edu_key]['graduation_time'])) {
                    $edu_key++;
                }
                $max_time_length = $max_time_length >= strlen($preg[0])?$max_time_length:strlen($preg[0]);
                $edu[$edu_key]['graduation_time'] = $preg[0];
                // $v = preg_replace("/$preg[0]/",'',$v);
                $v = str_replace($preg[0],'',$v);
            }
            if(preg_match($edu_config['educational'],$v,$preg)){
                if (isset($edu[$edu_key]['educational'])) {
                    $edu_key++;
                }
                $max_educational_length = $max_educational_length >= strlen($preg[0])?$max_educational_length:strlen($preg[0]);
                $edu[$edu_key]['educational'] = $preg[0];
                // $v = preg_replace("/$preg[0]/",'',$v);
                $v = str_replace($preg[0],'',$v);
            }
            if(preg_match($edu_config['school'],$v,$preg)){
                if (isset($edu[$edu_key]['school'])) {
                    $edu_key++;
                }
                $max_school_length = $max_school_length >= strlen($preg[0])?$max_school_length:strlen($preg[0]);
                $edu[$edu_key]['school'] = $preg[0];
                // $v = preg_replace("/$preg[0]/",'',$v);
                $v = str_replace($preg[0],'',$v);
            }
            $edu[$edu_key]['speciality'] = isset($edu[$edu_key]['speciality'])?$edu[$edu_key]['speciality'].$v:$edu[$edu_key]['speciality'] = $v;
            // $edu[$edu_key]['school'] = isset($edu[$edu_key]['school'])?'':$edu[$edu_key]['school'] = '';
            // $edu[$edu_key]['educational'] = isset($edu[$edu_key]['educational'])?'':$edu[$edu_key]['educational'] = '';
            // $edu[$edu_key]['graduation_time'] = isset($edu[$edu_key]['graduation_time'])?'':$edu[$edu_key]['graduation_time'] = '';
            $max_speciality_length = $max_speciality_length >= strlen($edu[$edu_key]['speciality'])?$max_speciality_length:strlen($edu[$edu_key]['speciality']);

        }
        foreach ($edu as $k => $v) {
               if (!isset($v['graduation_time'])) {
                   $v['graduation_time'] = '时间有误请自行补全';
                   $color = 'red';
               }
               if (!isset($v['school'])) {
                   $v['school'] = '学校有误请自行补全';
                   $color = 'red';
               }
               if ($v['speciality'] == '无法识别或缺少专业') {
                   $v['speciality'] = '专业有误请自行补全';
                   $color = 'red';
               }
               if (!isset($v['educational'])) {
                   $v['educational'] = '学历有误请自行补全';
                   $color = 'red';
               }

               $time_string = strlen($v['graduation_time']) >= $max_time_length?$v['graduation_time']:$v['graduation_time'].str_repeat(' ',($max_time_length-strlen($v['graduation_time']))/3*2);

               $school_string = strlen($v['school']) >= $max_school_length?$v['school']:$v['school'].str_repeat(' ',($max_school_length-strlen($v['school']))/3*2);
               
               $speciality_string = strlen($v['speciality']) >= $max_speciality_length?$v['speciality']:$v['speciality'].str_repeat(' ',($max_speciality_length-strlen($v['speciality']))/3*2);
               
               $educational_string = strlen($v['educational']) >= $max_educational_length?$v['educational']:$v['educational'].str_repeat(' ',($max_educational_length-strlen($v['educational']))/3*2);

               $edu_string = $time_string.'          '.$school_string.'          '.$speciality_string.'         '.$educational_string;
               $section->addText($edu_string,['color' => $color]);
               $section->addTextBreak(1);
           }
           // $section->addText(htmlspecialchars($error),['color' => $color]);



        /**********************************************旧方法，暂时不动*******************************************/

        // $educational_background = explode("\n",str_replace(' ','', $data['educational_background']));
        // $educational_background = implode("",$educational_background);
        // $edu_config = config('config.educationalBackground');
        // $edu = [];
        // $edu_string = '';
        // $edu_key = 0;
        // $section = new TextRun();
        // if(preg_match_all($edu_config['school'],$educational_background,$school)){
        //    preg_match_all($edu_config['graduation_time'],$educational_background,$graduation_time);
        //    preg_match_all($edu_config['speciality'],$educational_background,$speciality);
        //    preg_match_all($edu_config['educational'],$educational_background,$educational);

           
        //        // dump($school);
        //        // dump($graduation_time);
        //        // dump($speciality);
        //        // dump($educational);exit;
        //        $max_time_length = 0;
        //        $max_school_length = 0;
        //        $max_speciality_length = 0;
        //        $max_educational_length = 0;

        //        for ($i=0,$len = count($school[0]); $i < $len ; $i++) { 
        //            isset($school[0][$i])?$school_string = $school[0][$i]:$school_string = '';
        //            isset($graduation_time[0][$i])?$time_string = $graduation_time[0][$i]:$time_string = '';
        //            isset($speciality[0][$i])?$speciality_string = $speciality[0][$i]:$speciality_string = '';
        //            isset($educational[0][$i])?$educational_string = $educational[0][$i]:$educational_string = '';
        //            if ($speciality) {
        //                $speciality_string = str_replace($school_string,'',$speciality_string);
        //                $speciality_string = str_replace($time_string,'',$speciality_string);
        //                $speciality_string = str_replace($educational_string,'',$speciality_string);
        //            }
        //            $edu[$i]['time_string'] = $time_string;
        //            $edu[$i]['school_string'] = $school_string;
        //            $edu[$i]['speciality_string'] = $speciality_string;
        //            $edu[$i]['educational_string'] = $educational_string;
        //            $max_time_length = $max_time_length >= strlen($time_string)?$max_time_length:strlen($time_string);
        //            $max_school_length = $max_school_length >= strlen($school_string)?$max_school_length:strlen($school_string);
        //            $max_speciality_length = $max_speciality_length >= strlen($speciality_string)?$max_speciality_length:strlen($speciality_string);
        //            $max_educational_length = $max_educational_length >= strlen($educational_string)?$max_educational_length:strlen($educational_string);

        //        }
        //        $color = 'black';
        //        $error = '';
        //        if (count($school[0]) != count($graduation_time[0]) || count($school[0]) != count($speciality[0]) || count($school[0]) != count($educational[0])) {
        //            $color = 'red';
        //            $error = '数据可能会存在异常请自行检查后调整，如果没问题可忽略此条';
        //        }

        //        foreach ($edu as $k => $v) {
        //            $edu_string = strlen($v['time_string']) >= $max_time_length?$v['time_string']:$v['time_string'].str_repeat(' ',($max_time_length-strlen($v['time_string']))/3*2);

        //            $school_string = strlen($v['school_string']) >= $max_school_length?$v['school_string']:$v['school_string'].str_repeat(' ',($max_school_length-strlen($v['school_string']))/3*2);
                   
        //            $speciality_string = strlen($v['speciality_string']) >= $max_speciality_length?$v['speciality_string']:$v['speciality_string'].str_repeat(' ',($max_speciality_length-strlen($v['speciality_string']))/3*2);
                   
        //            $educational_string = strlen($v['educational_string']) >= $max_educational_length?$v['educational_string']:$v['educational_string'].str_repeat(' ',($max_educational_length-strlen($v['educational_string']))/3*2);

        //            $edu_string = $time_string.'          '.$school_string.'          '.$speciality_string.'         '.$educational_string;
        //            $section->addText($edu_string,['color' => $color]);
        //            $section->addTextBreak(1);
        //        }
        //        $section->addText(htmlspecialchars($error),['color' => $color]);
               
           

        // }

        /***************************************************************************************************************/
        $templateProcessor->setComplexBlock('edu_string',$section);

        
        $templateProcessor->saveAs($dest);

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

    // public function export(){
    //     //简历导出

    //     $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/file/'.'avo.docx';
    //     $templateProcessor = new TemplateProcessor($path);
    //     $templateProcessor->setValue('name','123');

    //     $templateProcessor->saveAs($path);
    //     dump($templateProcessor);exit;

    //     $dest = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/file/'.Session::get('user_info')['uname'].time().'.docx';
    //     $resume_id = input('get.resume_id');
    //     $type = input('get.type');

    //     $option = function($msg){
    //         if ($msg == 'tonghe') {
    //             //同和
    //             return ['image' => dirname(Env::get('ROOT_PATH')).'/client/src/image/tonghe.jpg','file_type' => '同和','image_size' => ['width'=>165,'height'=>53,'align'=>'right']];
    //         }
    //         else if ($msg == 'avo') {
    //             //大展
    //             return ['image' => dirname(Env::get('ROOT_PATH')).'/client/src/image/avo.jpg','file_type' => '大展','image_size' => ['width'=>90,'height'=>53,'align'=>'right']];
    //         }
    //         else{
    //             //缺少类型或者简历id
    //             echo json_encode(['msg' => $msg,'code' => 1]);exit;
    //         }
            
            
    //     };
    //     $resume_id == ''?$option('缺少简历ID'):'';
    //     $result = $type == ''?$option('缺少类型'):$option($type);

    //     $resume = new ResumeModel();
    //     $data = $resume->where('id','=',$resume_id)->find();

    //     if (!$data) {
    //         return json(['msg' => '数据不存在，请刷新','code' => 2,'data' => []]);
    //     }
    //     //开启phpword
    //     $PHPWord = new \PhpOffice\PhpWord\PhpWord();
    //     // $PHPWordHelper= new \PhpOffice\PhpWord\Shared\Font();
    //     $section = $PHPWord->createSection();

    //     //添加页眉
    //     $header = $section->createHeader();
    //     $table = $header->addTable();
    //     $table->addRow();
    //     $table->addCell(9000)->addText('');
    //     $cellStyle = array('borderBottomColor' => '#000000');
    //     $table->addCell(9000)->addImage($result['image'],$result['image_size']);
    //     //内容
    //     //自定义字体
    //     $PHPWord->addFontStyle('FangSong19pt', array('name'=>'仿宋', 'size'=>19,'bold' => true));
    //     $PHPWord->addFontStyle('FangSong16pt', array('name'=>'仿宋', 'size'=>16,'bold' => true));
    //     //姓名
    //     $section->addText('姓名:'.$data['name'],'FangSong19pt');
    //     //岗位
    //     $section->addText('面试职位:'.$data['expected_job'],'FangSong19pt');
    //     //基本信息
    //     $content = $section->addTable();
    //     $cellStyle = array('bgColor' => '#FAF0E6');
    //     $content->addRow();
    //     $content->addCell(9000,$cellStyle)->addText('基本信息','FangSong16pt');
    //     //基本信息填充
    //     $section->addTextBreak();
    //     $section->addText($data['sex'].' | '.$data['birthday'].' | '.$data['work_year'].'年工作经验');
    //     $section->addTextBreak();
    //     //自我评价
    //     $content = $section->addTable();
    //     $cellStyle = array('bgColor' => '#FAF0E6');
    //     $content->addRow();
    //     $content->addCell(9000,$cellStyle)->addText('自我评价','FangSong16pt');
    //     //自我评价填充
    //     $section->addTextBreak();
    //     $section->addText($data['selfEvaluation']);
    //     $section->addTextBreak();
    //     //工作经验
    //     $content = $section->addTable();
    //     $cellStyle = array('bgColor' => '#FAF0E6');
    //     $content->addRow();
    //     $content->addCell(9000,$cellStyle)->addText('工作经验','FangSong16pt');
    //     //工作经验填充
    //     $section->addTextBreak();
    //     $workExperience = explode("\n", $data['workExperience']);
    //     dump($data['workExperience']);exit;
    //     foreach ($workExperience as $k => $v) {
    //         $v = trim($v);
    //         // if(preg_match("/(.*)(带领|编写|工作|描述|负责|1,|1\.|1、)(.*)/",$v,$match)){
    //         //     $temp = str_replace($match[0],'',$v);

    //         //     $section->addText($temp);
    //         //     $section->addText($match[0]);
    //         //     continue;
    //         // }
    //         // $section->addText($v);
    //     }

    //     // $section->addText($data['workExperience']);
    //     $section->addTextBreak();
    //     //项目经验
    //     $content = $section->addTable();
    //     $cellStyle = array('bgColor' => '#FAF0E6');
    //     $content->addRow();
    //     $content->addCell(9000,$cellStyle)->addText('项目经验','FangSong16pt');
    //     //项目经验填充
    //     $section->addTextBreak();
    //     $projectExperience = explode("\n", $data['projectExperience']);
    //     foreach ($projectExperience as $k => $v) {
    //         $v = trim($v);
    //         // $section->addText($v);
    //     }
    //     // $section->addText($data['projectExperience']);
    //     $section->addTextBreak();
    //     //教育经历
    //     $content = $section->addTable();
    //     $cellStyle = array('bgColor' => '#FAF0E6');
    //     $content->addRow();
    //     $content->addCell(9000,$cellStyle)->addText('教育经历','FangSong16pt');
    //     //教育经历填充
    //     $section->addTextBreak();
    //     $educational_background = explode("\n", $data['educational_background']);
    //     $educational_background = implode("",$educational_background);
    //     $edu_config = config('config.educationalBackground');
    //     $edu = [];
    //     $edu_string = '';
    //     $edu_key = 0;

    //     if(preg_match_all($edu_config['school'],$educational_background,$school)){
    //        preg_match_all($edu_config['graduation_time'],$educational_background,$graduation_time);
    //        preg_match_all($edu_config['speciality'],$educational_background,$speciality);
    //        preg_match_all($edu_config['educational'],$educational_background,$educational);
    //        for ($i=0,$len = count($school[0]); $i < $len ; $i++) { 
    //            isset($school[0][$i])?$school_string = $school[0][$i]:$school_string = '';
    //            isset($graduation_time[0][$i])?$time_string = $graduation_time[0][$i]:$time_string = '';
    //            isset($speciality[0][$i])?$speciality_string = $speciality[0][$i]:$speciality_string = '';
    //            isset($educational[0][$i])?$educational_string = $educational[0][$i]:$educational_string = '';
    //            $edu_string = $time_string.' '.$school_string.' '.$speciality_string.' '.$educational_string;
    //            // dump($edu_string);exit;
    //            $section->addText($edu_string);
    //            $section->addTextBreak();

    //        }
    //     }

    //     // foreach ($edu_config as $k => $v) {
    //     //     if (preg_match($v,$educational_background,$res)) {
    //     //         $edu_string.= $res[0];
    //     //     }
    //     //     // $v = trim($v);
    //     //     // $section->addText($v);
    //     // }

    //     // $section->addText($data['educational_background']);
    //     $section->addTextBreak();


    //     //添加页脚
    //     $footer = $section->createFooter();
    //     $footer->addPreserveText('Page {PAGE} of {NUMPAGES}.',array('align'=>'center'));

    //     $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord,'Word2007');
    //     $objWriter->save($dest);

    //     header("Content-type:text/html;charset=utf-8"); 
    //     try{
    //         $fp = fopen($dest,"rb");
    //     }catch(\Exception $e){
    //         $this->error('文件已经不存在');
    //     }
    //     //下载
    //     $file_size=filesize($dest);

    //     //下载文件需要用到的头
    //     Header("Content-type: application/octet-stream");
    //     Header("Accept-Ranges: bytes"); 
    //     Header("Accept-Length:".$file_size); 
    //     Header("Content-Disposition: attachment; filename=".$result['file_type'].'-'.$data['name'].'-'.$data['expected_job'].'.docx'); 
    //     //================重点====================
    //     ob_clean();
    //     flush();
    //     //=================重点===================
    //     $buffer=1024; 
    //     $file_count=0; 
    //     //向浏览器返回数据 
    //     while(!feof($fp) && $file_count<$file_size){ 
    //         $file_con=fread($fp,$buffer); 
    //         $file_count+=$buffer; 
    //         echo $file_con; 
    //     }
    //     fclose($fp);


    // }

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
