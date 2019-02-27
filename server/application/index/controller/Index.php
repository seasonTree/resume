<?php
namespace app\index\controller;
use think\Controller;
//require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use think\facade\Session;
use think\facade\Env;
use app\index\model\Resume;
use app\index\model\Communicate;
use think\Db;
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

    public function indexList(){
    	//主页面板的统计数据
  //   	$begin_day = date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d')-1,date('Y')));

		// $end_day = date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d'),date('Y'))-1);

		// $begin_week = date("Y-m-d H:i:s",mktime(0,0,0,date("m"),date("d")-date("w")+1-7,date("Y")));
  //       $end_week = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));

        // $resume = new Resume();
  //       $communicate = new Communicate();
  //       $yesterday_resume = $resume->getCount("ct_time >= '$begin_day' and ct_time <= '$end_day'");
  //       $last_week_resume = $resume->getCount("ct_time >= '$begin_week' and ct_time <= '$end_week'");

  //       $yesterday_communicate = $communicate->getCount("ct_time >= '$begin_day' and ct_time <= '$end_day'");

  //       $last_week_communicate = $communicate->getCount("ct_time >= '$begin_week' and ct_time <= '$end_week'");

  //       return json(['msg' => '获取成功','code' => 0,'data' => ['yesterday_resume' => $yesterday_resume,'last_week_resume' => $last_week_resume,'yesterday_communicate' => $yesterday_communicate,'last_week_communicate' => $last_week_communicate]]);
           
           $data = [];//日期集合
           $length = 7;//时间数
           for ($i=1; $i <= $length; $i++) { 
               $date[] = date('Y-m-d',strtotime($i-$length.' day'));
           }

           $resume = new Resume();
           $communicate = new Communicate();
           $ct_user = Session::get('user_info')['uname'];
           $total = [];//汇总集合
           $person = [];//个人集合

           foreach ($date as $k => $v) {
               //汇总

               $total[$k]['date'] = preg_replace("/\d{4}\-/",'',$v);
               $total[$k]['resume'] = $resume->where("ct_time between '$v 00:00:00' and '$v 23:59:59'")->count();
               $total[$k]['commun'] = $communicate->where("date(communicate_time) between '$v 00:00:00' and '$v 23:59:59'")->count();
               //个人
               $person[$k]['date'] = preg_replace("/\d{4}\-/",'',$v);
               $person[$k]['resume'] = $resume->where("ct_time between '$v 00:00:00' and '$v 23:59:59' and ct_user='$ct_user'")->count();
               $person[$k]['commun'] = $communicate->where("date(communicate_time) between '$v 00:00:00' and '$v 23:59:59' and ct_user='$ct_user'")->count();
           }

           

           return json(['msg' => '获取成功','code' => 0,'data' => ['total_bar_data' => $total,'per_bar_data' => $person]]);
		
    }

    
}
