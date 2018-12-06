<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/Analysis.php';
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/phpanalysis/phpanalysis.class.php';

class Resume extends Controller
{	

    public function index()
    {
        return view('/index');
    }

    public function test(){
    	//测试解析内容
    	/*
    	 *尝试解析内容，
    	 */
    	// $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/rc1.htm';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/zgrc.doc';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/51job.html';
        $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/zl1.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/zl.doc';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/1.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/2.html';
    	// $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/zl1.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/1.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/2.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/3.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/4.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/5.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/6.html';
    	//地址
    	$obj = new \Analysis();

        $start1=microtime(true);

        $content = $obj->getContent($path,'string');


        $rule = config('config.resume_rule');
        // $date_rule = config('config.date_rule');

        foreach ($rule as $k => $v) {
            //处理特定字符格式
            $content = preg_replace("/$v/","\n".$v."\n",$content);
        }

        // while (preg_match($date_rule,$content,$preg)) {
        //      //处理时间格式
        //     $content = preg_replace($date_rule,"\n".$preg[0]."\n",$content);
        // }

        $content = explode("\n",$content);

        array_unshift($content,'基本资料');
        
        /*参数string 字符串结果集
         * 	  array 数组结果集
         *    html 原样输出
         */
        $begin = 0;
        $rule = "/^(基本资料|自我评价|目前状况|工作经历|工作经验|教育经历|教育背景|项目经验|项目经历|简历内容|技能特长|技能专长|专业技能|求职意向)/";
        $arr = [];//分类集合
        $lt_index = count($content) - 1;
        $resume_title = config('config.resume_title');
        foreach ($content as $k => $v) {
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



        dump($arr);

        /****************************分界线********************************/

        $list = [];
        foreach ($arr as $method => $parm) {
            $list[$method] = $this->$method($parm);
        }
        dump($list);
        $end1=microtime(true);  
        echo '执行时间'.($end1-$start1);
        // dump($content);exit;

        $start2=microtime(true);
        $content = $obj->getContent($path,'string');

        $get_data = $this->getData($content);
        dump($get_data);
        dump($content);
        $end2=microtime(true);  
        echo '执行时间'.($end2-$start2);
        exit;

        $url = 'http://cv-extract.com/api/extract';//接口
        $data = ['content' => $content];//参数
        $res = $obj->curlPost($url,$data);//结果集
        dump($res);

    }

    public function getData($str){
        //尝试获取内容
        $arr = [];
        $rule = config('config.rule');
        // $str = phpanalysis($str);
        // dump($str);
        foreach ($rule as $k => $v) {
            if (preg_match($v,$str,$preg)) {
                $strpos = strpos($str,$preg[0]);
                $strlen = strlen($preg[0]);
                for ($i = 0;$i < $strlen; $i++) {
                    $str[$strpos] = '';
                    $strpos++;
                }
                if ($k == 'birthday') {
                    $year = time() - strtotime($preg[0]);
                    if($year < 567648000){
                        continue;
                    }
                }
                unset($rule[$k]);
                $arr[$k] = $preg[0];
                // dump($rule);
            }
        }
        // dump($rule);
        return $arr;
    }



    public function basicData($parm){
    	//基本资料
        // dump($parm);exit;
        $arr = [];
        $rule = config('config.basicData');
        unset($parm[0]);
        foreach ($parm as $k => $v) {
            $v = $this->trimall($v);
            $v = preg_replace("/(智联招聘|前程无忧|匹配度(:|：)\d{2}%)|个人简历/",'',$v);
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
                    $arr[$n] = $preg[0];
                            
                    // dump($rule);
                }
                
            }
            
        }
        // dump($rule);
        // dump($arr);exit;
        // exit;
        return $arr;
    }

    public function jobIntention($parm){
    	//求职意向
        $arr = [];
        $rule = config('config.jobIntention');
        foreach ($parm as $k => $v) {
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

                }
                
            }
            
        }
        // dump($arr);exit;
        return $arr;

    }

    public function workExperience($parm){
    	//工作经历
        $rule = config('config.workExperience');
        $work_rule = $rule['work_rule'];
        unset($rule['work_rule']);
        unset($parm[0]);
        // $arr = [];
        // $begin = 0;
        // $last_index = count($parm) - 1;
        // foreach ($parm as $k => $v) {

        //     if (preg_match($work_rule, $v,$preg) || $k == $last_index) {
        //         $length = $k - $begin;
        //         $arr[] = array_slice($parm,$begin,$length);
        //         $begin = $k;
        //     }
        // }
        // unset($arr[0]);//删除第零个元素
        $work = [];
        /**********************分割线*********************************/
        // $key_num = 0;
        // foreach ($rule as $k => $v) {
        //     foreach ($parm as $n => $list) {
        //         if (preg_match($v, $work_rule,$preg)) {
        //             $key_num++;
        //         }
        //         if (preg_match($v, $list,$preg)) {

        //             $work[$key_num][$k] = $preg[0];
        //         }
        //     }
            
        // }
        $key_num = 0;
        $content = '';
        foreach ($parm as $n => $list) {
            $list = $this->trimall($list);
            foreach ($rule as $k => $v) {
                if (preg_match($work_rule, $list,$preg)) {
                    
                    $key_num = $n;

                }

                if (preg_match($v, $list,$preg)) {

                    $strpos = strpos($list,$preg[0]);
                    $strlen = strlen($preg[0]);

                    for ($i = 0;$i < $strlen; $i++) {
                        $list[$strpos] = '';
                        $strpos++;
                    }

                    $work[$key_num][$k] = trim(preg_replace("/:|：/",'',$preg[0]));
                    

                    
                }
            }
        }

        // foreach ($parm as $k => $v) {
        //     if (preg_match($work_rule, $v,$preg)) {
        //         $length = $k - $begin;
        //         $arr[] = array_slice($parm,$begin,$length);
        //         $begin = $k;
        //     }
        // }

        /*************************************************************/
        // foreach ($arr as $a => $list) {

        //     // foreach ($list as $n => $m) {
        //     //     preg_match($work_time,$m,$preg);
        //     //     $work[]['work_time'] = $preg[0];
        //     // }
            
        // }
        // dump($parm);
        // dump($arr);
        // dump($work);
        // dump($work);exit;
        return array_merge($work);


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
        $rule = config('config.projectExperience');
        $project_rule = $rule['project_rule'];
        unset($rule['project_rule']);
        unset($parm[0]);
        $project = [];
        $key_num = 0;
        foreach ($parm as $n => $list) {
            $list = $this->trimall($list);
            // dump($list);
            if (preg_match($project_rule, $list,$preg)) {
                
                $key_num = $n;

            }
            // dump($key_num);
            foreach ($rule as $k => $v) {
                

                if (preg_match($v, $list,$preg)) {

                    $strpos = strpos($list,$preg[0]);
                    $strlen = strlen($preg[0]);

                    for ($i = 0;$i < $strlen; $i++) {
                        $list[$strpos] = '';
                        $strpos++;
                    }

                    if ($k == 'project_time') {
                        $project[$key_num]['project_name'] = trim(preg_replace("/:|：/",'',$list));
                        // dump($project);
                    }

                    $project[$key_num][$k] = $preg[0];
                }
            }
             // dump($project);
            
        }
        // dump($parm);exit;
        return array_merge($project);

    }

    public function selfEvaluation($parm){
    	//自我评价
        return implode('',$parm);
    }

    public function skillExpertise($parm){
    	//技能专长

    }

    public function getResumeData(){
        //获取简历内容
        $content = input('content');
        if ($content == '') {
            return json(['code' => 1,'msg' => '缺少内容','data' => []]);
        }
        $rule = config('config.resume_rule');

        foreach ($rule as $k => $v) {
            //处理特定字符格式
            $content = preg_replace("/$v/","\n".$v."\n",$content);
        }

        $content = explode("\n",$content);

        array_unshift($content,'基本资料');
        // dump($content);exit;
        $begin = 0;
        $rule = "/^(基本资料|自我评价|目前状况|工作经历|工作业绩|工作经验|教育经历|教育背景|项目经验|项目经历|简历内容|技能特长|技能专长|专业技能|求职意向)/";
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
            $list[$method] = $this->$method($parm);
        }

        return json(['code' => 0,'msg' => '解析成功','data' => $list]);

    }

    function trimall($str){
        $qian = array(" ","　","\t","\n","\r");
        return str_replace($qian, '', $str);  
    }



}
