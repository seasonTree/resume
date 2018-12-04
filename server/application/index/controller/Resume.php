<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Env;
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/Analysis.php';

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
        $rule = "/^(自我评价|求职意向|目前状况|工作经历|工作经验|教育经历|教育背景|项目经验|项目经历|简历内容|技能特长|技能专长|专业技能)/";
        $arr = [];//分类集合
        $lt_index = count($content) - 1;
        $resume_title = config('config.resume_title');
        foreach ($content as $k => $v) {
        	if(preg_match($rule,$v,$preg) || $k == $lt_index){
        		$length = $k - $begin;
        		if ($k == $lt_index) {
        			$length = $length + 1;
        		}
                // $preg = isset($preg[0])?$preg[0] : '基本资料';
                // dump($preg);
        		$arr[] = array_slice($content,$begin,$length);
        		$begin = $k;
        	}

        }

        dump($arr);
        dump($content);exit;

        $url = 'http://cv-extract.com/api/extract';//接口
        $data = ['content' => $content];//参数
        $res = $obj->curlPost($url,$data);//结果集
        dump($res);

    }

    public function basicData(){
    	//基本资料

    }

    public function jobIntention(){
    	//求职意向
    }

    public function workExperience(){
    	//工作经历
    }

    public function educationalBackground(){
    	//教育背景
    }

    public function projectExperience(){
    	//项目经验
    }

    public function selfEvaluation(){
    	//自我评价
    }

    public function skillExpertise(){
    	//技能专长

    }

    public function nowStatus(){
        //目前状况
    }



}
