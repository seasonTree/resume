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
        $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/1.html';
        // $path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/2.html';
    	$path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/zl.html';
    	//地址
    	$obj = new \Analysis();
        $content = $obj->getContent($path,'array');
        array_unshift($content,'基本资料');
        
        /*参数string 字符串结果集
         * 	  array 数组结果集
         *    html 原样输出
         */
        $begin = 0;
        $rule = "/^(基本资料|自我评价|求职意向|目前状况|工作经历|工作经验|教育经历|教育背景|项目经验|项目经历|简历内容|技能特长|技能专长|专业技能)/";
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

                    $arr[$resume_title[$field]] = array_slice($content,$begin,$length);

                    $begin = $k;
                }

                if ($k != $lt_index) {
                    $field = $preg[0];
                }
                
        	}

        }

        dump($arr);
        $list = [];
        foreach ($arr as $method => $parm) {
            $list[$method] = $this->$method($parm);
        }
        dump($list);exit;
        dump($content);exit;

        $url = 'http://cv-extract.com/api/extract';//接口
        $data = ['content' => $content];//参数
        $res = $obj->curlPost($url,$data);//结果集
        dump($res);

    }

    public function basicData($parm){
    	//基本资料
        // dump($parm);exit;
        $arr = [];
        $rule = config('config.basicData');
        // $rule = "/(男|女)|\d{2}岁|0?(13|14|15|17|18|19)[0-9]{9}|\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}|(\d{1,2}|\d{1,2}.\d+)(年|年以上)工作经验/";
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
    }

    public function educationalBackground($parm){
    	//教育背景
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
