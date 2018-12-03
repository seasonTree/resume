<?php
namespace app\index\controller;
use think\Controller;
//require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use think\facade\Session;
use think\facade\Env;
require_once dirname(Env::get('ROOT_PATH')).'/server/extend/analysis.php';

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
    	$path = dirname(Env::get('ROOT_PATH')).'/client/dist/uploads/zgrc.doc';
    	//地址
    	$obj = new \analysis();
        $content = $obj->getContent($path,'string');
        /*参数string 字符串结果集
         * 	  array 数组结果集
         *    html 原样输出
         */
        dump($content);
    }

}
