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
           $total_comm = [];//沟通综合
           $person_comm = [];//个人沟通综合

           $screen_total = 0;
           $arrange_interview_total = 0;
           $arrive_total = 0;
           $approved_interview_total = 0;
           $entry_total = 0;

           $screen_person = 0;
           $arrange_interview_person = 0;
           $arrive_person = 0;
           $approved_interview_person = 0;
           $entry_person = 0;

           foreach ($date as $k => $v) {
               //汇总

               $total[$k]['date'] = preg_replace("/\d{4}\-/",'',$v);
               $total[$k]['resume'] = $resume->where("ct_time between '$v 00:00:00' and '$v 23:59:59'")->count();
               $total[$k]['commun'] = $communicate->where("date(communicate_time) between '$v 00:00:00' and '$v 23:59:59'")->count();
               //个人
               $person[$k]['date'] = preg_replace("/\d{4}\-/",'',$v);
               $person[$k]['resume'] = $resume->where("ct_time between '$v 00:00:00' and '$v 23:59:59' and ct_user='$ct_user'")->count();
               $person[$k]['commun'] = $communicate->where("date(communicate_time) between '$v 00:00:00' and '$v 23:59:59' and ct_user='$ct_user'")->count();


               $total_comm_temp = $communicate->where("date(communicate_time) between '$v 00:00:00' and '$v 23:59:59'")->field('screen,arrange_interview,arrive,approved_interview,entry')->select()->toArray();

               if (!empty($total_comm_temp)) {
                   $screen_total+= array_sum(array_column($total_comm_temp,'screen'));
                   $arrange_interview_total+= array_sum(array_column($total_comm_temp,'arrange_interview'));
                   $arrive_total+= array_sum(array_column($total_comm_temp,'arrive'));
                   $approved_interview_total+= array_sum(array_column($total_comm_temp,'approved_interview'));
                   $entry_total+=array_sum(array_column($total_comm_temp,'entry')); 

               }

               $person_comm_temp = $communicate->where("date(communicate_time) between '$v 00:00:00' and '$v 23:59:59' and ct_user='$ct_user'")->field('screen,arrange_interview,arrive,approved_interview,entry')->select()->toArray();

               if (!empty($person_comm_temp)) {
                   $screen_person+= array_sum(array_column($person_comm_temp,'screen'));
                   $arrange_interview_person+= array_sum(array_column($person_comm_temp,'arrange_interview'));
                   $arrive_person+= array_sum(array_column($person_comm_temp,'arrive'));
                   $approved_interview_person+= array_sum(array_column($person_comm_temp,'approved_interview'));
                   $entry_person+=array_sum(array_column($person_comm_temp,'entry')); 

               }

           }
           //总统计
           $total_comm['screen'] = $screen_total;
           $total_comm['arrange_interview'] = $arrange_interview_total;
           $total_comm['arrive'] = $arrive_total;
           $total_comm['approved_interview'] = $approved_interview_total;
           $total_comm['entry'] = $entry_total;
           //个人统计
           $person_comm['screen'] = $screen_person;
           $person_comm['arrange_interview'] = $arrange_interview_person;
           $person_comm['arrive'] = $arrive_person;
           $person_comm['approved_interview'] = $approved_interview_person;
           $person_comm['entry'] = $entry_person;
           $person_comm['screen_percentage'] = $screen_total != 0?round((($screen_person/$screen_total)*100),2).'%':'0%';
           $person_comm['arrange_interview_percentage'] = $arrange_interview_total !=0?round((($arrange_interview_person/$arrange_interview_total)*100),2).'%':'0%';
           $person_comm['arrive_percentage'] = $arrive_total != 0?round((($arrive_person/$arrive_total)*100),2).'%':'0%';
           $person_comm['approved_interview_percentage'] = $approved_interview_total != 0?round((($approved_interview_person/$approved_interview_total)*100),2).'%':'0%';
           $person_comm['entry_percentage'] = $entry_total != 0?round((($entry_person/$entry_total)*100),2).'%':'0%';

             
           /*****************************修改原有的七天显示格式，保留以前旧格式，**************************************/
           //处理统计数据
           $total = array_reverse($total);
           $resume_res = array_column($total,'resume');
           $commun_res = array_column($total,'commun');
           $total_bar_data[0]['date'] = '今天';//今日数据
           $total_bar_data[0]['resume'] = $resume_res[0];
           $total_bar_data[0]['commun'] = $commun_res[0];

           $total_bar_data[1]['date'] = '昨天';//昨日数据
           $total_bar_data[1]['resume'] = $resume_res[1];
           $total_bar_data[1]['commun'] = $commun_res[1];

           $total_bar_data[2]['date'] = '最近7日';//7日之内的数据
           $total_bar_data[2]['resume'] = array_sum($resume_res);
           $total_bar_data[2]['commun'] = array_sum($commun_res);

           //个人统计
           $person = array_reverse($person);
           $resume_res = array_column($person,'resume');
           $commun_res = array_column($person,'commun');
           $per_bar_data[0]['date'] = '今天';//今日数据
           $per_bar_data[0]['resume'] = $resume_res[0];
           $per_bar_data[0]['commun'] = $commun_res[0];

           $per_bar_data[1]['date'] = '昨天';//昨日数据
           $per_bar_data[1]['resume'] = $resume_res[1];
           $per_bar_data[1]['commun'] = $commun_res[1];

           $per_bar_data[2]['date'] = '最近7天';//7日之内的数据
           $per_bar_data[2]['resume'] = array_sum($resume_res);
           $per_bar_data[2]['commun'] = array_sum($commun_res);


           /********************************************************************************************************/



           

           return json(['msg' => '获取成功','code' => 0,'data' => ['total_bar_data' => $total_bar_data,'per_bar_data' => $per_bar_data,'total_comm' => $total_comm,'person_comm' => $person_comm]]);
		
    }

    
}
