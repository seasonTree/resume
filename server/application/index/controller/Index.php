<?php
namespace app\index\controller;
use think\Controller;
//require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use think\facade\Session;
use think\facade\Env;
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/Analysis.php';

class Index extends Controller
{
    public function index()
    {
        return view('/index');
    }

    public function browserChoose()
    {
        return view('/browser_choose');
    }

    public function test(){
    	//测试解析内容
    	/*
    	 *尝试解析内容，
    	 */
    	$path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/rc1.htm';
    	//地址
    	$obj = new \Analysis();
        $content = $obj->getContent($path,'string');
        /*参数string 字符串结果集
         * 	  array 数组结果集
         *    html 原样输出
         */
        // dump($content);

        $url = 'http://cv-extract.com/api/extract';//接口
        $data = ['content' => $content];//参数
        $res = $obj->curlPost($url,$data);//结果集
        dump($res);

    }

}
