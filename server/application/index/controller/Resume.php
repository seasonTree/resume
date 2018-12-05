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
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/zl1.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/zl.doc';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/1.html';
        $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/2.html';
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

        foreach ($rule as $k => $v) {
            $content = preg_replace("/$v/","\n".$v."\n",$content);
        }

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
        dump($rule);
        return $arr;
    }



    public function basicData($parm){
    	//基本资料
        // dump($parm);exit;
        $arr = [];
        $rule = config('config.basicData');
        unset($parm[0]);
        foreach ($parm as $k => $v) {
            // $v = phpanalysis($v);
            // dump($v);exit;
            foreach ($rule as $n => $pattern) {
                if (preg_match($pattern,$v,$preg)) {
                    $strpos = strpos($v,$preg[0]);
                    $strlen = strlen($preg[0]);
                    for ($i = 0;$i < $strlen; $i++) {
                        $v[$strpos] = '';
                        $strpos++;
                    }
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
        $work_rule = config('config.workExperience.work_rule');
        $arr = [];
        $begin = 0;
        $last_index = count($parm) - 1;
        foreach ($parm as $k => $v) {

            if (preg_match($work_rule, $v,$preg) || $k == $last_index) {
                $length = $k - $begin;
                $arr[] = array_slice($parm,$begin,$length);
                $begin = $k;
            }
        }
        unset($arr[0]);//删除第零个元素
        $work = [];
        $work_time = config('config.workExperience.work_time');
        foreach ($arr as $a => $list) {

            // foreach ($list as $n => $m) {
            //     preg_match($work_time,$m,$preg);
            //     $work[]['work_time'] = $preg[0];
            // }
            
        }
        dump($arr);
        dump($work);


    }

    public function educationalBackground($parm){
    	//教育背景
        $arr = [];
        $rule = config('config.educationalBackground');
        foreach ($parm as $k => $v) {
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
    }

    public function selfEvaluation($parm){
    	//自我评价
        return implode('',$parm);
    }

    public function skillExpertise($parm){
    	//技能专长

    }

    public function nowStatus($parm){
        //目前状况
    }



}
