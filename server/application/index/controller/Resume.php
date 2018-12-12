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
        $parm = implode("\n",$parm);
        $rule = config('config.resume_rule');
        $base_rule = config('config.base_rule');
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
            $v = preg_replace("/(基本资料|ID:\d+|招聘网|智联招聘|前程无忧|匹配度(:|：)\d{2}%)|个人简历/",'',$v);
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
        unset($parm[0]);
        $work = [];
        $key_num = 0;
        $content = '';
        $company_end = true;
        $job_end = true;
        $industry = true;
        $work_time_end = true;
        foreach ($parm as $k => $v) {
            $v = $this->trimall($v);
            $v = str_replace('_','',$v);
            if ($v == '') {
                continue;
            }
            // if (preg_match($rule['work_rule'],$v)) {

            //     $job_end = false;
            //     $industry = false;
            //     $company_end = false;
            //     $work_time_end = false;

            // }

            if (preg_match($rule['work_rule'],$v,$preg) || preg_match($rule['company_name'], $v)){
                // dump($company_end);
                // dump($work_time_end);
                // dump($job_end);
                // dump($industry);
                // echo '------------------------';
                if (preg_match($rule['work_rule'],$v)) {
                    $v = preg_replace("/:|：/",'',$v);
                }
                if (!preg_match($rule['special_characters'], $v)) {
                    if ($company_end == true && $work_time_end == true && $job_end == true && $industry == true) {

                        $job_end = false;
                        $industry = false;
                        $company_end = false;
                        $work_time_end = false;
                       
                        $key_num = $k;
                        
                    }
                }

            }

            if (preg_match($rule['work_time'],$v,$preg) && $work_time_end == false) {
                //检测是否存在时间
                $work[$key_num]['work_time'] = $preg[0];
                $v = str_replace($preg[0],'',$v);
                $v = preg_replace("/:|：/",'',$v);
                $work_time_end = true;
            }



            if (preg_match($rule['company_name'],$v,$preg) && $company_end == false) {
                $v = preg_replace("/:|：/",'',$v);
                if (preg_match($rule['company'],$v,$p)) {
                    if (!preg_match($rule['special_characters'], $p[0])) {
                        $work[$key_num]['company_name'] = $p[0];
                        $v = str_replace($p[0],'',$v);

                        $company_end = true;
                    }

                }
                
            }

            if ($v == '') {
                continue;
            }

            if (preg_match($rule['industry'],$v,$preg) && $industry == false) {

                $v = preg_replace("/:|：/",'',$v);

                if (!preg_match($rule['special_characters'], $v)) {
                    $work[$key_num]['industry'] = $v;
                    $v = str_replace($v,'',$v);
                    $industry = true;
                }

            }

            if ($v == '') {
                continue;
            }

            if (preg_match($rule['job'],$v,$preg) && $job_end == false) {
                $v = preg_replace("/:|：/",'',$v);
                $job = explode('|', $v)[0];
                if (!preg_match($rule['special_characters'], $job)) {
                    $work[$key_num]['job'] = $job;
                    $v = str_replace($v,'',$v);
                    $job_end = true;

                }
                
            }
            if ($v == '') {
                continue;
            }

            // if (preg_match($rule['work_time'],$v,$preg)) {

            //     if ($company_end == true) {
            //         if ($work_time_end == false) {
            //             $work[$key_num]['work_time'] = $preg[0];
            //             $v = str_replace($preg[0],'',$v);
            //             $work_time_end = true;
            //         }
                    
            //         if ($v == '') {
            //             continue;
            //         }
            //     }
            //     else{
            //         $key_num = $k;
            //         $work[$key_num]['work_time'] = $preg[0];
            //         // dump($preg[0]);
            //         $str = str_replace($preg[0],'',$v);
            //         $work_time_end = true;
            //         if ($str == '') {
            //             continue;
            //         }
            //         if (preg_match($rule['company'],$str,$preg)) {
            //             $work[$key_num]['company_name'] = $preg[0];
            //             $str = str_replace($preg[0],'',$v);
            //             if ($str != '') {
            //                 $work[$key_num]['job'] = $str;
            //                 $job_end = true;
            //                 continue;
            //             }
            //         }
            //         else{
            //             $work[$key_num]['job'] = $str;
            //             $job_end = true;
            //             continue;
            //         }
                   
            //     }
            // }
            
            // if ($industry == false) {
            //     if (preg_match($rule['industry'],$v,$preg)) {
            //         $work[$key_num]['industry'] = $preg[0];
            //         $v = str_replace($preg[0],'',$v);
            //     }
            //     else{
            //         if (preg_match("/[\x{4e00}-\x{9fa5}]+/u",$v,$preg)) {
            //             $arr = explode('|',$v);
            //             $has_job = false;
            //             if (count($arr) == 1) {
            //                 $work[$key_num]['job'] = $arr[0];
            //                 $job_end = true;
            //                 $has_job = true;
            //             }
            //             foreach ($arr as $a => $b) {
            //                 if (preg_match($rule['money'],$b)) {
            //                     $work[$key_num]['job'] = $arr[0];
            //                     $job_end = true;
            //                     $has_job = true;
            //                     break;
            //                 }
            //             }


            //             if ($has_job == true) {
            //                 continue;
            //             }
            //             $work[$key_num]['industry'] = $arr[0];
            //         }
            //         else{
            //             $work[$key_num]['industry'] = $v;
            //         }
                    
            //     }
                       
            //        $industry = true;
            //        continue;
                   
            // }
            // if ($job_end == false) {
            //         if(preg_match($rule['job'],$v,$preg)){
            //             $work[$key_num]['job'] = $preg[0];
            //             $v = str_replace($preg[0],'',$v);
            //         }
            //         else{
            //             // if (preg_match("/[\x{4e00}-\x{9fa5}]+/u",$v,$preg)) {
            //             //     dump($v);
            //             //     $work[$key_num]['job'] = $preg[0];
            //             // }
            //             // else{
            //                 $work[$key_num]['job'] = $v;
            //             // }
            //         }
                    
            //         $job_end = true;
            //         continue;
            // }
            if ($company_end == true && $work_time_end == true) {
                if ($industry == false) {
                    //行业匹配失败
                    $work[$key_num]['industry'] = '';
                    $industry = true;
                }
                if ($job_end == false) {
                    //职业匹配失败
                    $work[$key_num]['job'] = '';
                    $job_end = true;
                }
            }
            if ($job_end == true && $industry == true) {
                
                if (!isset($work[$key_num]['content'])) {
                    // if (preg_match($rule['special_characters'], $v)) {
                        $work[$key_num]['content'] = $v;
                    // }
                }
                else{
                    $work[$key_num]['content'].= $v;
                }
            }

        }
        // exit;
        return $work;


    }

    // public function workExperience($parm){
    //  //工作经历
    //     $rule = config('config.workExperience');
    //     $work_rule = $rule['work_rule'];
    //     unset($rule['work_rule']);
    //     unset($parm[0]);
    //     $work = [];
    //     $key_num = 0;
    //     $content = '';
    //     foreach ($parm as $n => $list) {
    //         $list = $this->trimall($list);
    //         if (preg_match($work_rule, $list,$preg)) {
                    
    //                 $key_num = $n;
                    

    //         }
    //         if (isset($work[$key_num]['content'])) {
    //             $work[$key_num]['content'].= $this->trimall($parm[$n]);
    //         }
    //         foreach ($rule as $k => $v) {
                

    //             if (preg_match($v, $list,$preg)) {

    //                 $strpos = strpos($list,$preg[0]);
    //                 $strlen = strlen($preg[0]);

    //                 for ($i = 0;$i < $strlen; $i++) {
    //                     $list[$strpos] = '';
    //                     $strpos++;
    //                 }

    //             if ($k == 'content') {
    //                 if (!isset($work[$key_num]['content'])) {
    //                     $work[$key_num][$k] = $preg[0];
    //                 }

    //                 if (!isset($work[$key_num]['job'])) {
    //                     $work[$key_num]['job'] = $this->trimall($parm[$n-1]);
    //                 }
    //                 continue;
    //             }
                
    //             if (isset($work[$key_num]['content'])) continue;

    //             if ($k == 'work_time') {
    //                 $company_name = trim(preg_replace("/:|：/",'',$list));
    //                     if (!preg_match("/(公司|集团|超市|科技|酒店)/",$company_name)) {
    //                         $work[$key_num]['company_name'] = $this->trimall($parm[$n - 1]);
    //                         $work[$key_num][$k] = trim(preg_replace("/:|：/",'',$preg[0]));
    //                         $work[$key_num]['job'] = trim(preg_replace("/:|：/",'',$list));
    //                         continue;
    //                     }
    //                     $work[$key_num]['company_name'] = trim(preg_replace("/:|：/",'',$list));
    //             }


    //             $work[$key_num][$k] = trim(preg_replace("/:|：/",'',$preg[0]));
                    

                    
    //             }
    //         }

    //     }

    //     return array_merge($work);


    // }

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
        // dump($parm);exit;
        $rule = config('config.projectExperience');//项目经验匹配规则
        unset($parm[0]);//删除第零个元素
        $project = [];//最终结果集合
        $project_time_end = true;
        $project_name_end = true;
        $envy_responsibility_end = true;//判断责任描述是否匹配结束的标记
        $job_end = true;
        $key_num = 0;

        foreach ($parm as $k => $v) {
            $v = $this->trimall($v);//转格式，去空格处理
            if ($v == '') {
                continue;
            }
            if (preg_match("/^(软件环境|硬件环境|开发工具|责任描述|当前状态)/",$v)) {
                continue;
            }
            if (preg_match($rule['project_time'],$v,$preg)) {
                $date = preg_replace("/\./",'/',$preg[0]);
                $v = str_replace($preg[0],'',$v);
                $v = $v.$date;

            }
            // dump($v);
            if (preg_match($rule['project_name'],$v,$preg) || preg_match($rule['project_rule'],$v)) {
                if (preg_match($rule['project_rule'],$v) && !preg_match("/(:|：)\S/", $v)) {
                    $v = preg_replace("/:|：/",'',$v);
                }
                if ($project_time_end == true && $project_name_end == true && $job_end == true && !preg_match($rule['special_characters'],$v)) {
                    $project_time_end = false;
                    $project_name_end = false;
                    $envy_responsibility_end = false;
                    $job_end = false;
                    $key_num = $k;
                }
            }
            if (preg_match($rule['project_time'],$v,$preg) && $project_time_end == false) {
                $v = preg_replace("/:|：/",'',$v);
                $project[$key_num]['project_time'] = $preg[0];
                
                $v = str_replace($preg[0],'',$v);
                $project_time_end = true;
            }
            
            if (!preg_match($rule['special_characters'],$v) && $project_name_end == false) {
                $v = preg_replace("/:|：/",'',$v);
                if (!preg_match("/^(项目描述|项目职责|项目责任|(\d+)|（\d+）)/",$v,$preg)) {
                    
                    $project[$key_num]['project_name'] = $v;
                    $v = str_replace($v,'',$v);
                    $project_name_end = true;
                }

            }
            if (preg_match($rule['job'],$v,$preg) && $job_end == false) {
                $v = preg_replace("/:|：/",'',$v);
                if (!preg_match($rule['special_characters'],$v)) {
                    if (!preg_match("/^(项目描述|项目职责|项目责任|(\d+)|（\d+）)/",$v,$preg)) {
                        $project[$key_num]['job'] = $v;
                        $v = str_replace($v,'',$v);
                        $job_end = true;
                    }
                }
                
            }
            if ($project_time_end == true && $project_name_end == true) {

                if ($job_end == false) {
                    //职位匹配失败。直接不匹配指向true
                    $project[$key_num]['job'] = '';
                    $job_end = true;
                    // continue;
                }
            }
            // if ($project_name_end == false) {
            //     //项目名称匹配失败。直接不匹配指向true
            //     $project[$key_num]['project_name'] = '';
            //     $job_end = true;
            // }

            if ($project_name_end == true && $project_time_end == true && $job_end == true) {
                // if (!isset($project[$key_num]['envy_responsibility'])) {
                //     if (preg_match($rule['envy_responsibility'],$v,$preg)) {
                //         $project[$key_num]['envy_responsibility'] = $v;
                //     }
                // }
                // else{
                //     $project[$key_num]['envy_responsibility'].= $v;
                // }

                if (!isset($project[$key_num]['content'])) {

                    // if (preg_match($rule['content'],$v,$preg)) {

                        // $project[$key_num]['content'] = $preg[0];
                    // }
                    // else{
                        $project[$key_num]['content'] = $v;
                    // }

                }
                else{
                    $project[$key_num]['content'].= $v;
                }
            }
            
        }
        // exit;
        return $project;
    }

    // public function projectExperience($parm){
    //     //项目经验
    //     $rule = config('config.projectExperience');//项目经验匹配规则
    //     unset($parm[0]);//删除第零个元素
    //     $project = [];//最终结果集合
    //     $mark = 0;//标记项目经验组下标
    //     $content_end = false;//判断项目经验是否匹配结束的标记
    //     $envy_responsibility_end = false;//判断责任描述是否匹配结束的标记
    //     $mark_arr = [];//记录下标（用户获取上一个项目经验组来使用）
    //     foreach ($parm as $k => $v) {
    //         $v = $this->trimall($v);//转格式，去空格处理
    //         if (preg_match($rule['project_rule'], $v)) {
    //             //项目时间/名字
                
    //             if (preg_match($rule['project_time'], $v,$preg)) {
                    
    //                 $project[$k]['project_time'] = $preg[0];
    //                 $v = preg_replace($rule['project_time'], '', $v);
    //                 $project[$k]['project_name'] = $v;
    //                 $mark = $k;
    //                 $envy_responsibility_end = false;
    //                 $content_end = false;
    //             }

    //             $end_key = end($mark_arr);

    //             if ($end_key) {
    //                 //检测最后一个数组中是否存在项目情况的内容，如果没有则表示匹配失败，直接截取这一段的内容作为填充(这种做法准确率降低)
    //                 if (!isset($project[$end_key]['content']) && !isset($project[$end_key]['envy_responsibility'])) {

    //                     $begin = $end_key;
    //                     $length = $k - $begin - 2;
    //                     $content = array_slice($parm,$begin,$length);

    //                     $project[$end_key]['content'] = implode('', $content);
    //                 }
                    
    //             }
    //             $mark_arr[] = $k;

    //         }

    //         if (!isset($project[$mark]['envy_responsibility']) && preg_match($rule['envy_responsibility'], $v,$preg)) {
    //             $content_end = true;
    //             $project[$mark]['envy_responsibility'] = $v;
    //             continue;
                
    //         }

    //         if (isset($project[$mark]['envy_responsibility']) && $envy_responsibility_end == false) {
    //             $project[$mark]['envy_responsibility'].= $v;
    //         }


    //         if (!isset($project[$mark]['content']) && preg_match($rule['content'], $v,$preg)) {
    //             $envy_responsibility_end = true;
    //             $project[$mark]['content'] = $v;
    //             continue;
                
    //         }

    //         if (isset($project[$mark]['content']) && $content_end == false) {
    //             $project[$mark]['content'].= $v;
    //         }
    //     }
    //     // exit;
    //     return $project;
    // }



    // public function projectExperience($parm){
    //  //项目经验
    //     $rule = config('config.projectExperience');
    //     $project_rule = $rule['project_rule'];
    //     unset($rule['project_rule']);
    //     unset($parm[0]);
    //     $project = [];
    //     $key_num = 0;
    //     $mark_arr = [];
    //     $last_index = count($parm) - 1;
    //     foreach ($parm as $n => $list) {
    //         $list = $this->trimall($list);
    //         // dump($list);
    //         if (preg_match($project_rule, $list,$preg)) {
    //             $key_num = $n;
    //             v

    //         }

    //         if ($n == $last_index) {
    //             $end_key = end($mark_arr);
    //             if (!isset($project[$end_key]['content']) && !isset($project[$end_key]['envy_responsibility'])) {
    //                 $begin = $end_key;
    //                 $length = $n - $begin - 1;
    //                 $content = array_slice($parm,$begin,$length);
    //                 $project[$end_key]['content'] = implode('', $content);
    //             }
                
    //         }

    //         if (isset($project[$key_num]['envy_responsibility'])) {

    //                     $project[$key_num]['envy_responsibility'].= $this->trimall($parm[$n]);
    //                     // continue;
    //         }
    //         if (isset($project[$key_num]['content'])) {

    //             $project[$key_num]['content'].= $this->trimall($parm[$n]);
    //             // continue;
    //         }
    //         foreach ($rule as $k => $v) {
                

    //             if (preg_match($v, $list,$preg)) {

    //                 $strpos = strpos($list,$preg[0]);
    //                 $strlen = strlen($preg[0]);

    //                 for ($i = 0;$i < $strlen; $i++) {
    //                     $list[$strpos] = '';
    //                     $strpos++;
    //                 }

    //                 if ($k == 'envy_responsibility') {
    //                     if (!isset($project[$key_num]['envy_responsibility'])) {
    //                         $project[$key_num]['envy_responsibility'] = $preg[0];
    //                     }
    //                     unset($rule['content']);
    //                     continue;
    //                 }


    //                 if ($k == 'content') {
    //                     if (!isset($project[$key_num]['content'])) {

    //                         $project[$key_num]['content'] = $preg[0];
    //                     }
    //                     unset($rule['envy_responsibility']);
    //                     continue;
    //                 }

    //                 if (isset($project[$key_num]['envy_responsibility'])) continue;

    //                 if (isset($project[$key_num]['content'])) continue;
                    

    //                 if ($k == 'project_time') {
    //                     $project_name = trim(preg_replace("/:|：/",'',$list));
    //                     // if (preg_match("/(任务分配|进度监督|系统架构|开发|技术总监|技术指导|架构师)/",$project_name)) {
    //                     //     //匹配去除时间后的关键字是否含有以上字段
    //                     //     $project_name = $this->trimall($parm[$n - 1]);
    //                     //     if (!str_replace('-','',$project_name)) {
    //                     //         //去除特殊字符-后是否包含项目名
    //                     //         $project[$key_num]['project_name'] = $project_name;
    //                     //         //项目名赋值
    //                     //     }
    //                     //     else{
    //                     //         //排除特殊情况的赋值
    //                     //         $project[$key_num]['project_name'] = trim(preg_replace("/:|：/",'',$list));
    //                     //     }
                            
    //                     //     $project[$key_num][$k] = $preg[0];
    //                     //     continue;
    //                     // }

    //                     $project_name = $this->trimall($parm[$n - 1]);
    //                         if (strlen($project_name) < 60 && preg_match("/([\x{4e00}-\x{9fa5}]+|\w+)/u",$project_name)) {
                                
    //                             $project[$key_num]['project_name'] = $project_name;
    //                         }
    //                         else{
    //                             $project[$key_num]['project_name'] = trim(preg_replace("/:|：/",'',$list));
    //                         }
                            
    //                         $project[$key_num][$k] = $preg[0];
    //                         continue;

    //                     $project[$key_num]['project_name'] = trim(preg_replace("/:|：/",'',$list));
    //                     // dump($project);
    //                 }

    //                 $project[$key_num][$k] = $preg[0];
    //             }
    //         }
            
            
    //     }
    //     // dump($project);
    //     // exit;
    //     // dump($parm);exit;
    //     return array_merge($project);

    // }

    public function selfEvaluation($parm){
        //自我评价
        return implode(" ",$parm);
    }

    public function skillExpertise($parm){
        //技能专长
        return implode(" ",$parm);
    }

    public function getResumeData(){
        //获取简历内容
        $content = input('content');
        if ($content == '') {
            return json(['code' => 1,'msg' => '缺少内容','data' => []]);
        }
        $rule = config('config.resume_rule');

        // $enter = ['软件环境','硬件环境','项目描述','责任描述','开发工具','(离职,正在找工作)','手机','当前状态'];
        foreach ($rule as $k => $v) {
            //处理特定字符格式
            $content = preg_replace("/$v/","\n".$v,$content);
        }
        // foreach ($enter as $a => $b) {
        //     $content = preg_replace("/$b/","\n".$b,$content);
        // }



        $content = explode("\n",$content);

        array_unshift($content,'基本资料');
        // dump($content);exit;
        $begin = 0;
        $rule = "/^(基本资料|自我评价|自我描述|自我介绍|目前状况|工作经历|工作业绩|工作经验|教育经历|教育背景|项目经验|项目经历|简历内容|技能特长|技能专长|专业技能|求职意向|作品展示)/";
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
